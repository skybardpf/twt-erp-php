<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class Sa_managerController extends Controller {
    public $menu_elem = 'legal.SettlementAccountManager';
	public $controller_title = 'Персона, управляющая расчетным счетом';

	/**
	 * Список управляющих расчетными счетами
	 */
	public function actionIndex() {
		$entities = SAManager::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр управляющего расчетным счетом
	 * @param $id
	 */
	public function actionView($id) {
		$entity = SAManager::model()->findByPk($id);
		$this->render('show', array('element' => $entity));
	}

	/**
	 * Удаление управляющего расчетным счетом
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model LegalEntities */
		$model = Beneficiary::model()->findByPk($id);
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
