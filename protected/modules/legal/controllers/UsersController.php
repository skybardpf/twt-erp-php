<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class UsersController extends Controller
{
	public function init()
	{
		$this->menu_elem = 'legal.Users';
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
		$entities = LUser::model()->findAll();
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