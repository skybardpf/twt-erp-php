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
	 * @param $id
	 */
	public function actionView($id) {
		$entity = InterestedPerson::model()->findByPk($id);
		$this->render('show', array('element' => $entity));
	}

	/**
	 * Удаление Заинтересованного лица
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model InterestedPerson */
		$model = InterestedPerson::model()->findByPk($id);
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
