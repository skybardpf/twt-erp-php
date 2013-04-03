<?php
/**
 * User: Forgon
 * Date: 25.02.13
 */
class Ledocument_typeController extends Controller {
	public $menu_elem = 'legal.LEDocument_type';
	public $controller_title = 'Типы документов';

	/**
	 * Список типов документов
	 */
	public function actionIndex() {
		$entities = LEDocumentType::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр типа документа
	 * @param $id
	 */
	public function actionView($id) {
		$model = LEDocumentType::model()->findByPk($id);
		$this->render('show', array('model' => $model));
	}

	public function actionAdd() {
		$model = new LEDocumentType();
		if(isset($_POST['ajax']) && $_POST['ajax']==='model-form-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		$error = '';

		if (isset($_POST[get_class($model)])) {
			// Нужно очистить ключи массива стран на случай ошибок валидации, чтобы скрипт добавления новых полей не создал поля с аналогичным номером.
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

	public function actionUpdate($id) {
		/** @var $model LEDocumentType */
		$model = LEDocumentType::model()->findByPk($id);
		if (empty($model)) throw new CHttpException(404);

		if(isset($_POST['ajax']) && $_POST['ajax']==='model-form-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		$error = '';
		if (isset($_POST[get_class($model)])) {
			// Нужно очистить ключи массива стран на случай ошибок валидации, чтобы скрипт добавления новых полей не создал поля с аналогичным номером.
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
		$this->render('update', array('model' => $model, 'error' => $error));
	}
	/**
	 * Удаление типа документа
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model LegalEntities */
		$model = LEDocumentType::model()->findByPk($id);
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
}