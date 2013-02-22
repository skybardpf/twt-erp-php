<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class CurrenciesController extends Controller
{
	public function init()
	{
		$this->menu_elem = 'legal.Currencies';
		parent::init();
	}
	public function actionIndex()
	{
		$entities = Currencies::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}
}