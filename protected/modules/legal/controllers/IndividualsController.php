<?php
/**
 * User: Forgon
 * Date: 01.04.13
 */
class IndividualsController extends Controller {
    
    public $layout = 'inner';
    public $menu_current = 'individuals';
    //public $menu_elem = 'legal.Individual';

	/**
	 * Список Физических лиц
	 */
	public function actionIndex() {
		$entities = Individuals::model()->where('deleted', false)->findAll();
		$this->render('index', array('elements' => $entities));
	}

	/**
	 * Просмотр физ.лица
	 * @param $id
	 */
	public function actionView($id) {
		$entity = Individuals::model()->findByPk($id);
		$this->render('show', array('element' => $entity));
	}

	/**
	 * Добавление Физ.лица
	 */
	public function actionAdd() {
		$model = new LegalEntities();
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
					//$this->redirect($this->createUrl('index'));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}
        
        $countries = Organizations::model('Countries')->findAll();
        $countries_arr = array();
        foreach($countries as $key => $country){
            $countries_arr[$country->id] = $country->name;
        }
        
		$this->render('update', array('model' => $model, 'countries' => $countries_arr, 'error' => $error));
	}

}
