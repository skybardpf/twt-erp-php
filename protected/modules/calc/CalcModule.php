<?php
/**
 * Class LegalModule
 *
 * @property string $assets
 */
class CalcModule extends CWebModule
{
	public $baseAssets = null;
	public $defaultController = 'request';

	public function init()
	{
        parent::init();

        Yii::setPathOfAlias('calc',dirname(__FILE__));
        $this->layoutPath = Yii::getPathOfAlias('calc.views.layouts');
        $this->layout = 'calc';

		// import the module-level models and components
		$this->setImport(array(
			'calc.models.*',
			'calc.components.*',
		));

        if (!$this->baseAssets) {
            $this->baseAssets = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('application.modules.calc.assets'),
                false,
                -1,
                YII_DEBUG
            );
        }
	}

	public function beforeControllerAction($controller, $action)
	{


        if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
