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
        $org = Organizations::model()->findByPk($org_id);
        if ($org === null) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
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
        $model = Contract::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'Не найден договор.');
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
        return Contract::model()
            ->where('id_yur', $org->primaryKey)
            ->where('deleted', false)
            ->findAll();
    }
}