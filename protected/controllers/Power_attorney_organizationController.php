<?php
/**
 *  Управление довереностями организации.
 *
 *  @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Power_attorney_organizationController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $pageTitle = 'TWT Consult | Организации | Доверенности';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'view' => 'application.controllers.PowerAttorneyOrganization.ViewAction',
            'edit' => 'application.controllers.PowerAttorneyOrganization.UpdateAction',
            'add' => 'application.controllers.PowerAttorneyOrganization.CreateAction',
            'delete' => 'application.controllers.PowerAttorneyOrganization.DeleteAction',

            '_html_form_select_element' => 'application.controllers.PowerAttorneyOrganization.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.controllers.PowerAttorneyOrganization.HtmlRowElementAction',
        );
    }
}
