<?php
/**
 * Управление организациями.
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
            'index' => 'application.modules.legal.controllers.Organization.IndexAction',
            'view' => 'application.modules.legal.controllers.Organization.ViewAction',
            'edit' => 'application.modules.legal.controllers.Organization.UpdateAction',
            'add' => 'application.modules.legal.controllers.Organization.CreateAction',
            'delete' => 'application.modules.legal.controllers.Organization.DeleteAction',

            /**
             * Redirect ContractorController
             */
            'get_activities_types' => 'application.modules.legal.controllers.Contractor.GetActivitiesTypesAction',
        );
    }

    /**
     * @return Organization Возвращаем созданную модель Организации.
     */
    public function createModel()
    {
        $model = new Organization();
        return $model;
    }
}