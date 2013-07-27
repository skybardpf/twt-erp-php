<?php
/**
 * User: Forgon
 * Date: 11.01.13
 */

//ТУДУ: Банки
class BanksController extends Controller
{
	public $menu_elem = 'legal.Banks';
	public $controller_title = 'Банки';

	/**
	 * List action
	 */
	public function actionIndex() {
		// TODO пока что только банки кипра
		$entities = Banks::model()->where('deleted', false)->where('country', '196')->findAll();
		$this->render('index', array('elements' => $entities));
	}

	public function actionShow($id)
	{
		$model = Banks::model()->findByPk($id);
		$this->render('show');
	}
}