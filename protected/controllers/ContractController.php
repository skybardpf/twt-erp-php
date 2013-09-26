<?php
/**
 * Управление договорами для определенной организации.
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
}