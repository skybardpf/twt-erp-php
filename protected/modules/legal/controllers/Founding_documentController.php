<?php
/**
 * User: Forgon
 * Date: 25.02.13
 */
class Founding_documentController extends Controller {
	public $menu_elem = 'legal.FoundingDocument';
	public $controller_title = 'Учредительные документы';

	/**
	 * Редактирование Учредительного документа
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionUpdate($id) {
		$model = FoundingDocument::model()->findByPk($id);
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
	 * Добавление Учредительного документа
	 */
	public function actionAdd() {
		$model = new FoundingDocument();
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
	 * Список учредительных документов
	 */
	public function actionIndex() {
		$entities = FoundingDocument::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр учредительного документа
	 * @param $id
	 */
	public function actionView($id) {
		$model = FoundingDocument::model()->findByPk($id);
		$this->render('show', array('model' => $model));
	}

	/**
	 * Удаление учредительнго документа
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model LegalEntities */
		$model = FoundingDocument::model()->findByPk($id);
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