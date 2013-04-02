<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class Power_attorney_leController extends Controller {
	public $menu_elem = 'legal.PowerAttorneyLE';
	public $controller_title = 'Доверенности Юр.Лиц';

	/**
	 * Список доверенностей
	 */
	public function actionIndex() {
		$entities = PowerAttorneysLE::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр доверенности
	 * @param $id
	 */
	public function actionView($id) {
		$model = PowerAttorneysLE::model()->findByPk($id);
		$this->render('show', array('model' => $model));
	}

	/**
	 * Удаление доверенности
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model PowerAttorneysLE */
		$model = PowerAttorneysLE::model()->findByPk($id);
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
