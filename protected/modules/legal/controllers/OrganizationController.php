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

    public $pageTitle = 'TWT Consult | Мои организации';

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
        );
    }

    /**
     * Список организаций.
     * @return Organization[]
     */
    public function getDataProvider()
    {
        $cache_id = get_class(Organization::model()).'_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = Organization::model()->where('deleted', false)->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
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