<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	/**
	 * @var string array key for currently active menu element
	 */
	public $menu_elem = 'main';

	public $asset_static = '';

	protected function beforeAction($action)
	{
		if (!$this->asset_static) {
            $this->asset_static = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('application.assets'),
                false,
                -1,
                YII_DEBUG
            );
        }
		return parent::beforeAction($action);
	}

    /**
     *  Получаем модель организации.
     *
     *  @param string $org_id
     *  @param bool $force_cache
     *  @return Organization
     *  @throws CHttpException
     */
    public function loadOrganization($org_id, $force_cache = false)
    {
        $cache_id = get_class(Organization::model()).'_'.$org_id;
        $org = Yii::app()->cache->get($cache_id);
        if ($force_cache || $org === false){
            $org = Organization::model()->findByPk($org_id);
            if ($org === null) {
                throw new CHttpException(404, 'Не найдена организация.');
            }
            Yii::app()->cache->set($cache_id, $org);
        }
        return $org;
    }
}