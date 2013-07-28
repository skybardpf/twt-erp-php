<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class CurrenciesController extends Controller {
	public $menu_elem = 'legal.Currencies';
	public $controller_title = 'Валюты';

	/**
	 * Список валют
	 */
	public function actionIndex() {
		$entities = Currencies::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}
}