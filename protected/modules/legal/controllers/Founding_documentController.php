<?php
/**
 * User: Forgon
 * Date: 25.02.13
 */
class Founding_documentController extends Controller
{
	public function init() {
		$this->menu_elem = 'legal.FoundingDocument';
		parent::init();
	}

	/**
	 * List action
	 */
	public function actionIndex() {
		$entities = FoundingDocument::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	public function actionView($id)
	{
		$model = FoundingDocument::model()->findByPk($id);
		$this->render('show', array('model' => $model));
	}
}