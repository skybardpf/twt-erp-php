<?php
/**
 * User: Forgon
 * Date: 11.01.13
 */

class CountriesController extends Controller {
	public $menu_elem = 'legal.Countries';
	public $controller_title = 'Страны юрисдикции';

	/**
	 * Список стран
	 */
	public function actionIndex() {
		$entities = Countries::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}
}