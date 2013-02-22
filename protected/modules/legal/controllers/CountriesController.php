<?php
/**
 * User: Forgon
 * Date: 11.01.13
 */

class CountriesController extends Controller
{
	public function init()
	{
		$this->menu_elem = 'legal.Countries';
		parent::init();
	}

	public function actionIndex()
	{
		$entities = Countries::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}
}