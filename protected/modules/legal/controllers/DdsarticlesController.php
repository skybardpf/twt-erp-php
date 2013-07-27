<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class DdsarticlesController extends Controller {
	public $menu_elem = 'legal.DDSArticles';
	public $controller_title = 'Статьи движения денежных стредств';

	/**
	 * Список статей движения денежных средств
	 */
	public function actionIndex() {
		$entities = DDSArticle::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}
}