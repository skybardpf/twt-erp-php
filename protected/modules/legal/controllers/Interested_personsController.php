<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class Interested_personsController extends Controller {
    public $menu_elem = 'legal.InterestedPerson';
	public $controller_title = 'Заинтересованное лицо';

	/**
	 * Список Заинтересованных лиц
	 */
	public function actionIndex() {
		$entities = InterestedPerson::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр Заинтересованного лица
	 * @param $id       Идентификатор физ.лица
	 * @param $id_yur   Идентификатор Юр.лица
	 * @param $role     Роль
	 */
	public function actionView($id, $id_yur, $role) {
		$entity = InterestedPerson::model()->findByPk($id, $id_yur, $role);
		$this->render('show', array('element' => $entity));
	}

	/**
	 * Удаление Заинтересованного лица
	 *
	 * @param $id
	 * @param $id_yur
	 * @param $role
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id, $id_yur, $role) {
		/** @var $model InterestedPerson */
		$model = InterestedPerson::model()->findByPk($id, $id_yur, $role);
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
	 * Редактирование заинтересованного лица
	 *
	 * @param $id
	 * @param $id_yur
	 * @param $role
	 *
	 * @throws CHttpException
	 */
	public function actionUpdate($id, $id_yur, $role) {
		$model = InterestedPerson::model()->findByPk($id, $id_yur, $role);
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
