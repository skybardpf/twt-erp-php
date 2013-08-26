<?php
/**
 * Управление Контрагентами.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ContractorController extends Controller
{
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
            'index' => 'application.controllers.Contractor.IndexAction',
            'view' => 'application.controllers.Contractor.ViewAction',
            'edit' => 'application.controllers.Contractor.UpdateAction',
            'add' => 'application.controllers.Contractor.CreateAction',
            'delete' => 'application.controllers.Contractor.DeleteAction',

            'get_activities_types' => 'application.controllers.Contractor.GetActivitiesTypesAction',

            '_html_form_select_element' => 'application.controllers.Contractor.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.controllers.Contractor.HtmlRowElementAction',

        );
    }
}