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

	public function actionAdd()
	{
		$this->render('add');
	}

	public function actionDelete()
	{
		$this->render('delete');
	}

	public function actionIndex()
	{
		$entities = Currencies::model()->findAll();


		$this->render('index', array('elements' => $entities));
	}

	public function actionShow()
	{
		$this->render('show');
	}

	public function actionUpdate()
	{
		$this->render('update');
	}
}