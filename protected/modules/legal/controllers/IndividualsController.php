<?php
/**
 * User: Forgon
 * Date: 01.04.13
 */
class IndividualsController extends Controller {
    public $menu_elem = 'legal.Individual';
	public $controller_title = 'Физические лица';

	/**
	 * Список Физических лиц
	 */
	public function actionIndex() {
		$entities = Individuals::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр физ.лица
	 * @param $id
	 */
	public function actionView($id) {
		$entity = Individuals::model()->findByPk($id);
		$this->render('show', array('element' => $entity));
	}

}
