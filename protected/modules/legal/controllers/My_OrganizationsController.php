<?php
/**
 * User: Forgon
 * Date: 23.04.13
 */
class My_OrganizationsController extends Controller {

	public $layout = 'inner';
	public $menu_current = 'legal';
	public $cur_tab = '';

	public function actionIndex() {
		$models = Organizations::model()->where('deleted', false)->findAll();
		$this->render('list', array('models' => $models));
	}

	public function actionShow($id) {
		$this->cur_tab      = 'info';
		$model = Organizations::model()->findByPk($id);
		$this->render('show', array('tab_content' => $this->renderPartial('tab_info', array('model' => $model), true), 'model' => $model));
	}
}