<?php
/**
 * Управление Контрагентами.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ContractorController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'contractors';
    public $pageTitle = 'TWT Consult | Контрагенты';

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