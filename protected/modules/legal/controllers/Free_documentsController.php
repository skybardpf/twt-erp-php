<?php
/**
 * User: Forgon
 * Date: 25.02.13
 */
class Free_documentsController extends Controller {
	public $menu_elem = 'legal.FreeDocument';
	public $controller_title = 'Свободные документы';

	/**
	 * Редактирование свободного документа
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionUpdate($id) {
		/** @var $model FoundingDocument */
		$model = FreeDocument::model()->findByPk($id);
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

	/**
	 * Добавление свободного документа
	 */
	public function actionAdd() {
		$model = new FreeDocument();
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
	 * Список документов
	 */
	public function actionIndex() {
		$entities = FreeDocument::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр свободного документа
	 * @param $id
	 */
	public function actionView($id) {
		$model = FreeDocument::model()->findByPk($id);
		$this->render('show', array('model' => $model));
	}
}