<?php
/**
 *  Управление договорами для определенной организации.
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ContractController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $pageTitle = 'TWT Consult | Мои организации | Договора';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'list' => 'application.modules.legal.controllers.Contract.ListAction',
            'view' => 'application.modules.legal.controllers.Contract.ViewAction',
            'edit' => 'application.modules.legal.controllers.Contract.UpdateAction',
            'add' => 'application.modules.legal.controllers.Contract.CreateAction',
            'delete' => 'application.modules.legal.controllers.Contract.DeleteAction',

            '_html_modal_select_signatory' => 'application.modules.legal.controllers.Contract.HtmlModalSelectSignatoryAction',
            '_html_row_signatory' => 'application.modules.legal.controllers.Contract.HtmlRowSignatoryAction',
        );
    }

    /**
     * @param string $id Идентификатор договора.
     * @return Contract
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $cache_id = get_class(Contract::model()).'_'.$id;
        $model = Yii::app()->cache->get($cache_id);
        if ($model !== false){
            $model = Contract::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найден договор.');
            }
            Yii::app()->cache->set($cache_id, $model, 0);
        }
        return $model;
    }

    /**
     * Создаем новый договор.
     * @param Organization $org
     * @return Contract
     */
    public function createModel(Organization $org)
    {
        $model = new Contract();
        $model->id_yur = $org->primaryKey;
        return $model;
    }

    /**
     * Получаем список договоров указанной организации.
     * @param Organization $org
     * @return Contract[]
     */
    public function getDataProvider(Organization $org)
    {
        $cache_id = get_class(Contract::model()).'_list_org_id_'.$org->primaryKey;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = Contract::model()
                ->where('id_yur', $org->primaryKey)
                ->where('deleted', false)
                ->findAll();

            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}