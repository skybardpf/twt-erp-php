<?php
/**
 * User: Forgon
 * Date: 01.04.13
 */
class IndividualsController extends Controller {
    public $menu_elem = 'legal.Individual';
	public $controller_title = 'Физические лица';

	public function actionIndex() {
		$entities = Individuals::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}
}
