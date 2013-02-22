<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class DdsarticlesController extends Controller
{
	public function init()
	{
		$this->menu_elem = 'legal.DDSArticles';
		parent::init();
	}

	public function actionIndex()
	{
		$entities = DDSArticle::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}
}