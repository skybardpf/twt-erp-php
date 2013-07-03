<?php
/**
 * User: Forgon
 * Date: 23.04.13
 */
class My_organizationsController extends Controller {

	public $layout = 'inner';
	/** @var string Пункт левого меню */
	public $menu_current = 'legal';
	/** @var string Вкладка верхнего меню одной организации */
	public $cur_tab = '';
	/** @var Organizations Текущая просматриваемая организация */
	public $organization = NULL;

	/**
	 * Список моих организаций
	 */
	public function actionIndex()
    {
		$models = Organizations::model()->where('deleted', false)->findAll();
		$this->render('list', array('models' => $models));
	}

	/**
	 * Просмотр организации
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionView($id)
    {
		/** @var $model Organizations */
		$model = Organizations::model()->findByPk($id);
		if (!$model) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }
//		$this->organization = $model;

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/my_organizations/tab_info',
                array(
                    'organization' => $model
                ), true),
            'organization' => $model,
            'cur_tab' => 'info',
        ));
	}

	public function actionDelete($id)
    {
		$ret = array();
		if (!Organizations::model()->delete_by_id($id)) {
			$ret['error'] = 'Организацию удалить невозможно';
		}
		echo CJSON::encode($ret);
		if (Yii::app()->request->isAjaxRequest) {
			Yii::app()->end();
		} elseif (YII_DEBUG) {
			CVarDumper::dump($ret,5,1);
			$this->renderText('Debug_out');
		} else {
			throw new CHttpException(404);
		}
	}

    /**
     *  Добавление новой организации.
     *
     *  @throws CHttpException
     */
    public function actionAdd()
    {
        /** @var $org Organizations */
        $org = new Organizations();

        $error = '';
        if ($_POST && !empty($_POST['Organizations'])) {
            $org->setAttributes($_POST['Organizations']);
            if ($org->validate()) {
                try {
                    $org->save();
                    $this->redirect($this->createUrl('index'));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/my_organizations/form',
                array(
                    'model' => $org,
                    'error' => $error,
                ), true),
            'organization' => $org,
            'cur_tab' => 'info',
        ));
    }

    /**
     *  Редактирование организации.
     *
     *  @param  string $id
     *  @throws CHttpException
     */
    public function actionEdit($id)
    {
        /** @var $org Organizations */
        $org = Organizations::model()->findByPk($id);
        if (!$org) {
            throw new CHttpException(404, 'Не найдено юр. лицо.');
        }

        $error = '';
        if ($_POST && !empty($_POST['Organizations'])) {
            $org->setAttributes($_POST['Organizations']);
            if ($org->validate()) {
                try {
                    $org->save();
                    $this->redirect($this->createUrl('view', array('id' => $id)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/my_organizations/form',
                array(
                    'model' => $org,
                    'error' => $error,
                ), true),
            'organization' => $org,
            'cur_tab' => 'info',
        ));
    }

// ╔══════════════╗
// ║ Доверенности old ║
// ╚══════════════╝

//	/**
//	 * Создание учредительного документа
//	 *
//	 * @param $id
//	 *
//	 * @throws CHttpException
//	 */
//	public function actionAdd_attorney($id) {
//		$this->cur_tab = 'documents';
//
//		/** @var $model Organizations */
//		$model = Organizations::model()->findByPk($id);
//		if (!$model) throw new CHttpException(404);
//		$this->organization = $model;
//
//		$doc = new PowerAttorneysLE();
//		$doc->id_yur    = $id;
//		$doc->type_yur  = "Организации";
//		$doc->from_user = true;
//		$doc->user      = SOAPModel::USER_NAME;
//		$error = '';
//		if ($_POST && !empty($_POST['PowerAttorneysLE'])) {
//			$doc->setAttributes($_POST['PowerAttorneysLE']);
//			if ($doc->validate()) {
//				try {
//					$doc->save();
//					$this->redirect($this->createUrl('documents', array('id' => $id)));
//				} catch (Exception $e) {
//					$error = $e->getMessage();
//				}
//			}
//		}
//		$this->render('documents/attorney_form', array('doc' => $doc, 'error' => $error));
//	}

	/**
	 * Редактирование учредительного документа
	 *
	 * @param $id
	 *
	 * @throws CHttpException
	 */
//	public function aaaaaaaaaaaaaaaaaaaaaaactionEdit_founding($id) {
//		$this->cur_tab = 'documents';
//
//		/** @var $doc FoundingDocument */
//		$doc = FoundingDocument::model()->findByPk($id);
//		if (!$doc) throw new CHttpException(404);
//		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');
//
//		/** @var $org Organizations */
//		$org = Organizations::model()->findByPk($doc->id_yur);
//		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
//		$this->organization = $org;
//
//		$doc->user      = SOAPModel::USER_NAME;
//		$error = '';
//		if ($_POST && !empty($_POST['FoundingDocument'])) {
//			$doc->setAttributes($_POST['FoundingDocument']);
//			if ($doc->validate()) {
//				try {
//					$doc->save();
//					$this->redirect($this->createUrl('show_founding', array('id' => $id)));
//				} catch (Exception $e) {
//					$error = $e->getMessage();
//				}
//			}
//		}
//		$this->render('documents/founding_form', array('doc' => $doc, 'error' => $error));
//	}
//
//	/**
//	 * Просмотр учредительного документа
//	 *
//	 * @param $id
//	 *
//	 * @throws CHttpException
//	 */
//	public function aaaaaaaaaaaaaaaaaaaaaaactionShow_founding($id) {
//		$this->cur_tab = 'documents';
//
//		/** @var $doc FoundingDocument */
//		$doc = FoundingDocument::model()->findByPk($id);
//		if (!$doc) throw new CHttpException(404);
//		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');
//
//		/** @var $org Organizations */
//		$org = Organizations::model()->findByPk($doc->id_yur);
//		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
//		$this->organization = $org;
//
//		$this->render('documents/founding_show', array('model' => $doc));
//	}
//
//	public function aaaaaaaaaaaaaaaaaaaaaaactionDelete_founding($id) {
//		$this->cur_tab = 'documents';
//
//		/** @var $doc FoundingDocument */
//		$doc = FoundingDocument::model()->findByPk($id);
//		if (!$doc) throw new CHttpException(404);
//		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');
//
//		/** @var $org Organizations */
//		$org = Organizations::model()->findByPk($doc->id_yur);
//		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
//		$this->organization = $org;
//
//		if (Yii::app()->request->isAjaxRequest) {
//			$ret = array();
//			try {
//				$doc->delete();
//			} catch (Exception $e) {
//				$ret['error'] = $e->getMessage();
//			}
//			echo CJSON::encode($ret);
//			Yii::app()->end();
//		} else {
//			if (isset($_POST['result'])) {
//				switch ($_POST['result']) {
//					case 'yes':
//						if ($doc->delete()) {
//							$this->redirect($this->createUrl('documents', array('id' => $this->organization->primaryKey)));
//						} else {
//							throw new CHttpException(500, 'Не удалось удалить учредительный документ');
//						}
//						break;
//					default:
//						$this->redirect($this->createUrl('show_founding', array('id' => $doc->primaryKey)));
//						break;
//				}
//			}
//			$this->render('delete_founding', array('model' => $doc));
//		}
//	}
//
//	public function actionDocument_Add($yur_id, $doc_type, $id = false){
//        if(empty($_POST['PowerAttorneysLE']))
//        { // вгружаем форму
//            $form_url_params = array();
//            switch($doc_type){
//                case 'pa':
//                    if($id){
//                        $model = PowerAttorneysLE::model()->findByPk($id);
//                        $form_url_params['id'] = $id;
//                    }
//                    else{
//                        $model = PowerAttorneysLE::model();
//                    }
//
//                    $individuals = Individuals::model()->findAll();
//                    $individuals_arr = array();
//                    foreach($individuals as $individual){
//                        $individuals_arr[$individual->id] = $individual->name;
//                    }
//
//                    $this->render('documents/add_pa', array('model' => $model, 'individuals' => $individuals_arr, 'url_params' => $form_url_params));
//                    break;
//            }
//        } else {
//        // если POST не пустой, значит сохраняем форму и редиректим на страницу организации
//			switch($doc_type){
//                case 'pa':
//                    /** @var $model PowerAttorneysLE */
//	                $model = PowerAttorneysLE::model();
//                    foreach($_POST['PowerAttorneysLE'] as $parameter => $value){
//                        $model->$parameter = $value;
//                    }
//	                $model->scans = array();
//	                $model->id_yur = $yur_id;
//                    break;
//            }
//            if($id){
//                $model->id = $id;
//            }
//            //CVarDumper::dump($model);
//            $model->save();
//	        // Вернемся к просмотру организации
//            $this->redirect($this->createUrl('/legal/my_organizations/documents', array('id', $yur_id)));
//            //$this->actionDocuments('000000003');
//        }
//    }
//
//    public function actionDocument_show($id) {
//        $this->menu_current = 'index';
//        $this->cur_tab = 'documents';
//        $model = Organizations::model()->findByPk($id);
//        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/documents/show', array('id' => $id), true), 'model' => $model));
//    }

//    public function actionSettlement_add($id) {
//        $this->menu_current = 'index';
//        $this->cur_tab = 'settlements';
//        $model = Organizations::model()->findByPk($id);
//        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/settlement/add', array('id' => $id), true), 'model' => $model));
//    }

//    public function actionSettlement_show($id) {
//        $this->menu_current = 'index';
//        $this->cur_tab = 'settlements';
//        $model = Organizations::model()->findByPk($id);
//        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/settlement/show', array('id' => $id), true), 'model' => $model));
//    }





}