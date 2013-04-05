<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class BeneficiaryController extends Controller {
    public $menu_elem = 'legal.Beneficiary';
	public $controller_title = 'Бенефициары';

	/**
	 * Список Бенефициаров
	 */
	public function actionIndex() {
		$entities = Beneficiary::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр Бенефициара
	 * @param $id
	 * @param $id_yur
	 */
	public function actionView($id, $id_yur) {
		$entity = Beneficiary::model()->findByPk($id, $id_yur);
		$this->render('show', array('element' => $entity));
	}

	/**
	 * Удаление Бенефициара
	 * @param $id
	 * @param $id_yur
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id, $id_yur) {
		/** @var $model LegalEntities */
		$model = Beneficiary::model()->findByPk($id, $id_yur);
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
