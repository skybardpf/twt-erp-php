<?php
/**
 * Управление организациями.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class OrganizationController extends Controller
{
	public $layout = 'inner';
	/** @var string Пункт левого меню */
	public $menu_current = 'legal';
	/** @var string Вкладка верхнего меню одной организации */
	public $cur_tab = '';

    public $pageTitle = 'TWT Consult | Организации';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.controllers.Organization.IndexAction',
            'view' => 'application.controllers.Organization.ViewAction',
            'edit' => 'application.controllers.Organization.UpdateAction',
            'add' => 'application.controllers.Organization.CreateAction',
            'delete' => 'application.controllers.Organization.DeleteAction',

            /**
             * Redirect ContractorController
             */
            'get_activities_types' => 'application.controllers.Contractor.GetActivitiesTypesAction',
        );
    }
}