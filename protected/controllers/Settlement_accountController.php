<?php
/**
 * Банковские счета -> Список для всех юр. лиц.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see SettlementAccount
 */
class Settlement_accountController extends Controller {
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
            'index' => 'application.controllers.Settlement_account.IndexAction',
            'list' => 'application.controllers.Settlement_account.ListAction',
            'add' => 'application.controllers.Settlement_account.CreateAction',
            'view' => 'application.controllers.Settlement_account.ViewAction',
            'edit' => 'application.controllers.Settlement_account.UpdateAction',
            'delete' => 'application.controllers.Settlement_account.DeleteAction',

            'get_bank_name' => 'application.controllers.Settlement_account.GetBankNameAction',
            'selected_managing_persons' => 'application.controllers.Settlement_account.SelectedManagingPersonsAction',
        );
    }

//    /**
//     * Список банковских счетов.
//     * @return SettlementAccount[]
//     */
//    public function getDataProvider()
//    {
//        $cache_id = get_class(SettlementAccount::model()).'_list';
//        $data = Yii::app()->cache->get($cache_id);
//        if ($data === false){
//            $data = SettlementAccount::model()
//                ->where('deleted', false)
//                ->findAll();
//            Yii::app()->cache->set($cache_id, $data);
//        }
//        return $data;
//    }
//
//    /**
//     * Список банковских счетов для организации.
//     * @param Organization $org
//     * @return SettlementAccount[]
//     */
//    public function getDataProviderForOrganization(Organization $org)
//    {
//        $cache_id = get_class(SettlementAccount::model()).'_list_org_id_'.$org->primaryKey;
//        $data = Yii::app()->cache->get($cache_id);
//        if ($data === false){
//            $data = SettlementAccount::model()
//                ->where('deleted', false)
//                ->where('id_yur', $org->primaryKey)
//                ->findAll();
//            Yii::app()->cache->set($cache_id, $data);
//        }
//        return $data;
//    }
//
//    /**
//     * @param string $id Идентификатор банковского счета.
//     * @return SettlementAccount
//     * @throws CHttpException
//     */
//    public function loadModel($id)
//    {
//        $cache_id = get_class(SettlementAccount::model()).'_'.$id;
//        $model = Yii::app()->cache->get($cache_id);
//        if ($model === false){
//            $model = SettlementAccount::model()->findByPk($id);
//            if ($model === null) {
//                throw new CHttpException(404, 'Не найден банковский счет.');
//            }
//            Yii::app()->cache->set($cache_id, $model, 0);
//        }
//        return $model;
//    }
//
//    /**
//     * Создаем новый банковский счет.
//     * @param Organization $org
//     * @return SettlementAccount
//     */
//    public function createModel(Organization $org)
//    {
//        $model = new SettlementAccount();
//        $model->id_yur = $org->primaryKey;
//        return $model;
//    }
}
