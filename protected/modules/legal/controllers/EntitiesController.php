<?php

class EntitiesController extends Controller
{
	public function init()
	{
		$this->menu_elem = 'legal.entities';
		parent::init();
	}

	/**
	 * Add new entity
	 */
	public function actionAdd() {
		$model = new LegalEntities();
		$error = '';
		if (isset($_POST[get_class($model)])) {
			$model->setAttributes($_POST[get_class($model)]);
			if ($model->validate()) {
				try {
					$model->save();
					$this->redirect($this->createUrl('index'));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}
		$this->render('add', array('model' => $model, 'error' => $error));
	}

	/**
	 * Delete entity
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model LegalEntities */
		$model = LegalEntities::model()->findByPk($id);
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
	 * List action
	 */
	public function actionIndex() {
		// Юр лица (не контрагенты и не удалены)
		$entities = LegalEntities::model()->where('deleted', false)->where('contragent', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Show legal entity
	 * @param $id
	 */
	public function actionView($id) {
		$entity = LegalEntities::model()->findByPk($id);
		$this->render('show', array('element' => $entity));
	}

	/**
	 * Update entity
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionUpdate($id) {
		$model = LegalEntities::model()->findByPk($id);
		if (empty($model)) throw new CHttpException(404);

		if(isset($_POST['ajax']) && $_POST['ajax']==='model-form-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		$error = '';
		if (isset($_POST[get_class($model)])) {
			$model->setAttributes($_POST[get_class($model)]);
			if ($model->validate()) {
				try {
					$model->save();
					//$this->redirect($this->createUrl('index'));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}
		$this->render('update', array('model' => $model, 'error' => $error));
	}
}