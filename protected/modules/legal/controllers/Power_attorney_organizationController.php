<?php
/**
 *  Управление довереностями организации.
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
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
            'view' => 'application.modules.legal.controllers.PowerAttorneyOrganization.ViewAction',
            'edit' => 'application.modules.legal.controllers.PowerAttorneyOrganization.UpdateAction',
            'add' => 'application.modules.legal.controllers.PowerAttorneyOrganization.CreateAction',
            'delete' => 'application.modules.legal.controllers.PowerAttorneyOrganization.DeleteAction',

            '_html_form_select_element' => 'application.modules.legal.controllers.PowerAttorneyOrganization.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.modules.legal.controllers.PowerAttorneyOrganization.HtmlRowElementAction',
        );
    }
}
