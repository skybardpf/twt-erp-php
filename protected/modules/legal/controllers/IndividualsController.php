<?php
/**
 * User: Forgon
 * Date: 01.04.13
 */
class IndividualsController extends Controller {
    
    public $layout       = 'inner';
    public $menu_current = 'individuals';
	public $cur_tab      = '';

	/**
	 * Список Физических лиц
	 */
	public function actionIndex() {
		$entities = Individuals::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 *  Просмотр физ.лица
     *
	 *  @param  string $id
     *  @throws CHttpException
	 */
	public function actionView($id) {
		$this->cur_tab = 'view';
		$entity = Individuals::model()->findByPk($id);
        if (!$entity){
            throw new CHttpException(404, 'Не найдено физ. лицо.');
        }
		$this->render(
			'show',
			array(
				'model' => $entity,
				'tab_content' => $this->renderPartial(
                    'tab_view',
                    array(
                        'element' => $entity
                    ),
                    1
                )
			)
		);
	}

	/**
	 * Добавление Физ.лица
	 */
	public function actionAdd() {
		$model = new Individuals();
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
	 * Удаление Физ.лица
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionDelete($id) {
		/** @var $model LegalEntities */
		$model = Individuals::model()->findByPk($id);
		if (empty($model)) throw new CHttpException(404);

		if (Yii::app()->request->isAjaxRequest) {
			$ret = array();
			try {
				$model->delete();
			} catch (Exception $e) {
				$ret['error'] = $e->getMessage();
			}
			echo CJSON::encode($ret);
			Yii::app()->end();
		} else {
			if (isset($_POST['result'])) {
				switch ($_POST['result']) {
					case 'yes':
						if ($model->delete()) {
							$this->redirect($this->createUrl('index'));
						} else {
							throw new CHttpException(500, 'Не удалось удалить лицо');
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

	/**
	 * Редактирование Физ.лица
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionEdit($id) {
		$model = Individuals::model()->findByPk($id);
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
					$this->redirect($this->createUrl('index'));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}

		$this->render('update', array('model' => $model, 'error' => $error));
	}

	public function actionCart($id) {
		$this->cur_tab = 'cart';
		$entity = Individuals::model()->findByPk($id);
		$this->render(
			'show',
			array(
				'model' => $entity,
				'tab_content' => "Заглушка" // Todo Корзина акционирования
			)
		);
	}

}
