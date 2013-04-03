<?php
/**
 * User: Forgon
 * Date: 03.04.13
 */
class Settlement_accountsController extends Controller {
    public $menu_elem = 'legal.SettlementAccount';
	public $controller_title = 'Расчетные счета';

	/**
	 * Список расчетных счетов
	 */
	public function actionIndex() {
		$entities = SettlementAccount::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр Расчетного счета
	 * @param $id
	 */
	public function actionView($id) {
		$entity = SettlementAccount::model()->findByPk($id);
		$this->render('show', array('element' => $entity));
	}
}
