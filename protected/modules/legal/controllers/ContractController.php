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
     *  Получаем модель организации.
     *
     *  @param string $org_id
     *  @return Organizations
     *  @throws CHttpException
     */
    public function loadOrganization($org_id)
    {
        $cache_id = get_class(Organizations::model()).'_'.$org_id;
        $org = Yii::app()->cache->get($cache_id);
        if ($org === false){
            $org = Organizations::model()->findByPk($org_id);
            if ($org === null) {
                throw new CHttpException(404, 'Не найдено юридическое лицо.');
            }
            Yii::app()->cache->set($cache_id, $org, 0);
        }

        return $org;
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
        if ($model === false){
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
     * @param Organizations $org
     * @return Contract
     */
    public function createModel(Organizations $org)
    {
        $model = new Contract();
        $model->id_yur = $org->primaryKey;
        return $model;
    }

    /**
     * Получаем список договоров указанной организации.
     * @param Organizations $org
     * @return Contract[]
     */
    public function getDataProvider(Organizations $org)
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