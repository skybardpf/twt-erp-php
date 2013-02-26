<?php
/**
 * User: Forgon
 * Date: 26.02.13
 */

class CalcController extends Controller
{
	public $menu_elem = 'legal.Calc';
	public $controller_title = 'Страховой калькулятор';

	/**
	 * List action
	 */
	public function actionIndex() {
		Calc::getCategories();
		//$entities = Banks::model()->findAll();
		$this->renderText('asdasd');
		//$this->render('index', array('elements' => $entities));
	}

	public function actionShow($id)
	{
		$model = Banks::model()->findByPk($id);
		$this->render('show');
	}
}