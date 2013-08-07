<?php
/**
 * Управление Контрагентами.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ContractorController extends Controller{
    const TAB_MENU_INFO = 'info';
    const TAB_MENU_POWER_ATTORNEY = 'power_attorney';

    public $layout = 'inner';
    public $menu_current = 'contractors';
    public $pageTitle = 'TWT Consult | Контрагенты';

    public $current_tab_menu = self::TAB_MENU_INFO;

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.modules.legal.controllers.Contractor.IndexAction',
            'view' => 'application.modules.legal.controllers.Contractor.ViewAction',
            'edit' => 'application.modules.legal.controllers.Contractor.UpdateAction',
            'add' => 'application.modules.legal.controllers.Contractor.CreateAction',
            'delete' => 'application.modules.legal.controllers.Contractor.DeleteAction',

            'get_activities_types' => 'application.modules.legal.controllers.Contractor.GetActivitiesTypesAction',

            '_html_form_select_element' => 'application.modules.legal.controllers.Contractor.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.modules.legal.controllers.Contractor.HtmlRowElementAction',

        );
    }
}