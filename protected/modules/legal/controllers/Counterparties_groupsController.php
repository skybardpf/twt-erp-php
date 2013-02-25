<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class Counterparties_groupsController extends Controller {

	public $menu_elem = 'legal.Counterparties_groups';
	public $controller_title = 'Группы контрагентов';

	/**
	 * Добавление группы контрагентов
	 * @param bool $pid
	 */
	public function actionAdd($pid = false) {
		$elem = new CounterpartiesGroups();
		if ($pid) {
			$parent = CounterpartiesGroups::model()->findByPk($pid);
			$elem->parent = $pid;
		} else {
			$parent = NULL;
		}

		$error = array();
		if (isset($_POST['CounterpartiesGroups'])) {
			$elem->setAttributes($_POST['CounterpartiesGroups']);
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
	 * Удаление группы контрагентов
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model LegalEntities */
		$model = CounterpartiesGroups::model()->findByPk($id);
		if (empty($model)) throw new CHttpException(404);
		if (Yii::app()->request->isAjaxRequest) {
			$model->delete();
		}
		if (isset($_POST['result'])) {
			switch ($_POST['result']) {
				case 'yes':
					if ($model->delete()) {
						$this->redirect($this->createUrl('index'));
					} else {
						//throw new CException('Не удалось удалить страницу');
					}
					break;
				default:
					$this->redirect($this->createUrl('show', array('id' => $model->id)));
					break;
			}
		}
		$this->render('delete', array('model' => $model));
	}

	/**
	 * Список групп контрагентов
	 * @param bool $pid
	 */
	public function actionIndex($pid = false) {
		$model = CounterpartiesGroups::model();
		$parent = NULL;
		if ($pid) {
			$parent = $model->findByPk($pid);
			//$model->where('parent', $pid);
		} else {
			$parent = NULL;
			//$model->where('level', '0');
		}
		$entities = $model->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities, 'parent' => $parent));
	}

	/**
	 * Редактирование группы контрагентов
	 * @param $id
	 */
	public function actionUpdate($id) {
		$model = CounterpartiesGroups::model()->findByPk($id);
		$error = array();
		if (isset($_POST['CounterpartiesGroups'])) {
			$model->setAttributes($_POST['CounterpartiesGroups']);
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