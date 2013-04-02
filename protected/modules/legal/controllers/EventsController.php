<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class EventsController extends Controller {
    public $menu_elem = 'legal.Events';
	public $controller_title = 'Мероприятия';

	/**
	 * Список мероприятий
	 */
	public function actionIndex() {
		$entities = Events::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр мероприятия
	 * @param $id
	 */
	public function actionView($id) {
		$entity = Events::model()->findByPk($id);
		$this->render('show', array('element' => $entity));
	}

	/**
	 * Удаление мероприятия
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model Events */
		$model = Events::model()->findByPk($id);
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
					$this->redirect($this->createUrl('view', array('id' => $model->id)));
					break;
			}
		}
		$this->render('delete', array('model' => $model));
	}
}
