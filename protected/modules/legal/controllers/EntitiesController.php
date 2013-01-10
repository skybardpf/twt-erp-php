<?php

class EntitiesController extends Controller
{
	public function init()
	{
		$this->menu_elem = 'legal.entities';
		parent::init();
	}

	public function actionAdd()
	{
		$this->render('add');
	}

	public function actionDelete($id)
	{
		/** @var $model LegalEntities */
		$model = LegalEntities::model()->findByPk($id);
		if (empty($model)) throw new CHttpException(404);
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

	public function actionIndex()
	{
		$entities = LegalEntities::model()->findAll();
		$this->render('index', array('elements' => $entities));
	}

	public function actionShow($id)
	{
		$entity = LegalEntities::model()->findByPk($id);
		$this->render('show', array('element' => $entity));
	}

	public function actionUpdate($id)
	{
		$model = LegalEntities::model()->findByPk($id);
		if (empty($model)) throw new CHttpException(404);

		if(isset($_POST['ajax']) && $_POST['ajax']==='model-form-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if (isset($_POST[get_class($model)])) {
			$model->setAttributes($_POST[get_class($model)]);
			if ($model->save()) {
				$this->redirect($this->createUrl('index'));
			}
		}
		$this->render('update', array('model' => $model));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}