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

	public function actionIndex()
	{
		$entities = LUser::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}
}