<?php
/**
 * User: Forgon
 * Date: 26.02.13
 */
class PegroupController extends Controller {
	public $menu_elem = 'legal.Pegroup';
	public $controller_title = 'Группы физ.лиц';

	/**
	 * Список групп физ.лиц
	 * @param bool $pid
	 */
	public function actionIndex($pid = false) {
		$model = PEGroup::model();
		$parent = NULL;
		if ($pid) {
			$parent = $model->findByPk($pid);
			//$model->where('parent', $pid);
		} else {
			$parent = NULL;
			//$model->where('level', '0');
		}
		$elements = $model->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $elements, 'parent' => $parent));
	}

	/**
	 * Добавление группы физ.лиц.
	 */
	public function actionAdd($pid = false) {
		$elem = new PEGroup();
		if ($pid) {
			$parent = PEGroup::model()->findByPk($pid);
			$elem->parent = $pid;
		} else {
			$parent = NULL;
		}

		$error = array();
		if (isset($_POST['PEGroup'])) {
			$elem->setAttributes($_POST['PEGroup']);
			if ($elem->validate()) {
				try {
					$elem->save();
					$this->redirect($this->createUrl('index', ($parent ? array('pid' => $pid) : array())));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}

		$this->render('add', array('model' => $elem, 'parent' => $parent, 'error' => $error));
	}

	/**
	 * Редактирование группы физ.лиц
	 * @param $id
	 */
	public function actionUpdate($id) {
		$model = PEGroup::model()->findByPk($id);
		$error = array();
		if (isset($_POST['PEGroup'])) {
			$model->setAttributes($_POST['PEGroup']);
			if ($model->validate()) {
				try {
					$model->save();
					$this->redirect($this->createUrl('index', array()));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}
		$this->render('update', array('model' => $model, 'error' => $error));
	}
}
