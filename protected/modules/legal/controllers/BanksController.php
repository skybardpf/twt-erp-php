<?php
/**
 * User: Forgon
 * Date: 11.01.13
 */

class BanksController extends Controller
{
	public function init() {
		$this->menu_elem = 'legal.Banks';
		parent::init();
	}

	/**
	 * List action
	 */
	public function actionIndex() {
		$entities = Banks::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}

	public function actionShow($id)
	{
		$model = Banks::model()->findByPk($id);
		$this->render('show');
	}
}