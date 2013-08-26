<?php
/**
 * Управление договорами для определенной организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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
            'list' => 'application.controllers.Contract.ListAction',
            'view' => 'application.controllers.Contract.ViewAction',
            'edit' => 'application.controllers.Contract.UpdateAction',
            'add' => 'application.controllers.Contract.CreateAction',
            'delete' => 'application.controllers.Contract.DeleteAction',

            '_html_modal_select_signatory' => 'application.controllers.Contract.HtmlModalSelectSignatoryAction',
            '_html_row_signatory' => 'application.controllers.Contract.HtmlRowSignatoryAction',
        );
    }

    /**
     * Получаем список договоров указанной организации.
     * @param Organization $org
     * @return Contract[]
     */
//    public function getDataProvider(Organization $org)
//    {
//        $cache_id = get_class(Contract::model()).'_list_org_id_'.$org->primaryKey;
//        $data = Yii::app()->cache->get($cache_id);
//        if ($data === false){
//            $data = Contract::model()
//                ->where('id_yur', $org->primaryKey)
//                ->where('deleted', false)
//                ->findAll();
//
//            Yii::app()->cache->set($cache_id, $data);
//        }
//        return $data;
//    }
}