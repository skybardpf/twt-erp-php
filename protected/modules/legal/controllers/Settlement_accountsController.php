<?php
/**
 * Банковские счета -> Список для всех юр. лиц.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @see SettlementAccounts
 */
class Settlement_accountsController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'settlements';

    public $pageTitle = 'TWT Consult | Мои счета';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.modules.legal.controllers.Settlement_accounts.IndexAction',
            'list' => 'application.modules.legal.controllers.Settlement_accounts.ListAction',
            'add' => 'application.modules.legal.controllers.Settlement_accounts.CreateAction',
            'view' => 'application.modules.legal.controllers.Settlement_accounts.ViewAction',
            'edit' => 'application.modules.legal.controllers.Settlement_accounts.UpdateAction',
            'delete' => 'application.modules.legal.controllers.Settlement_accounts.DeleteAction',

            'get_bank_name' => 'application.modules.legal.controllers.Settlement_accounts.GetBankNameAction',
            'selected_managing_persons' => 'application.modules.legal.controllers.Settlement_accounts.SelectedManagingPersonsAction',
        );
    }

    /**
     * Список банковских счетов.
     * @return SettlementAccount[]
     */
    public function getDataProvider()
    {
        $cache_id = get_class(SettlementAccount::model()).'_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = SettlementAccount::model()
                ->where('deleted', false)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список банковских счетов для организации.
     * @param Organization $org
     * @return SettlementAccount[]
     */
    public function getDataProviderForOrganization(Organization $org)
    {
        $cache_id = get_class(SettlementAccount::model()).'_list_org_id_'.$org->primaryKey;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = SettlementAccount::model()
                ->where('deleted', false)
                ->where('id_yur', $org->primaryKey)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param string $id Идентификатор банковского счета.
     * @return SettlementAccount
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $cache_id = get_class(SettlementAccount::model()).'_'.$id;
        $model = Yii::app()->cache->get($cache_id);
        if ($model === false){
            $model = SettlementAccount::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найден банковский счет.');
            }
            Yii::app()->cache->set($cache_id, $model, 0);
        }
        return $model;
    }

    /**
     * Создаем новый банковский счет.
     * @param Organization $org
     * @return SettlementAccount
     */
    public function createModel(Organization $org)
    {
        $model = new SettlementAccount();
        $model->id_yur = $org->primaryKey;
        return $model;
    }
}
