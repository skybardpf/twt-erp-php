<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class UsersController extends Controller {
	public $menu_elem = 'legal.Users';
	public $controller_title = 'Пользователи';

	/**
	 * Список пользователей
	 */
	public function actionIndex() {
		$entities = LUser::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}
}