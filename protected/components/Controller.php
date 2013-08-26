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

        $identity = new UserIdentity('demo','demo');
        if($identity->authenticate()){
            Yii::app()->user->login($identity, 3600*24*7);
        } else {
            echo $identity->errorMessage;
        }

		return parent::beforeAction($action);
	}
}