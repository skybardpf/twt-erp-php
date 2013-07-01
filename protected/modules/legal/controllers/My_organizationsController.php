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
	public function actionIndex() {
		$models = Organizations::model()->where('deleted', false)->findAll();
		$this->render('list', array('models' => $models));
	}

	/**
	 * Просмотр организации
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionShow($id) {
		$this->cur_tab      = 'info';

		/** @var $model Organizations */
		$model = Organizations::model()->findByPk($id);
		if (!$model) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }
		$this->organization = $model;

		$this->render('tab_info');
	}

	public function actionDelete($id) {
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
    public function actionAdd($id = false){
        Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/organizations/one.js', CClientScript::POS_HEAD);

        $form_url_params = array();
        if(empty($_POST['Organizations'])){ // вгружаем форму
            if($id){
                $model = Organizations::model()->findByPk($id);
                $form_url_params['id'] = $id;
            }
            else{
                $model = Organizations::model();
            }
            $countries = Countries::getValues();
            $this->render('add', array('model' => $model, 'countries' => $countries, 'url_params' => $form_url_params));
        }
        else{ // если POST не пустой, значит сохраняем форму и редиректим на страницу организации
            $organization = Organizations::model();

            foreach($_POST['Organizations'] as $parameter => $value){
                $organization->$parameter = $value;
            }
            if($id){
                $organization->id = $id;
            }
            //CVarDumper::dump($organization);
            //CVarDumper::dump($organization->save());
            $result = $organization->save();
            if(!$result['error'])
                $this->actionShow($result['id']);
            else{
                $countries = Countries::getValues();
                $this->render('add', array('model' => $organization, 'countries' => $countries, 'url_params' => $form_url_params, 'error_message' => $result['errorMessage']));
            }
        }
    }
    public function actionEdit($id){
        $this->actionAdd($id);
    }

	/**
	 * Список документов организации
	 * @param $id Идентификатор организации
	 *
	 * @throws CHttpException
	 */
	public function actionDocuments($id){
		$this->cur_tab = 'documents';

	    /** @var $model Organizations */
	    $model = Organizations::model()->findByPk($id);
	    if (!$model) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }
		$this->organization = $model;

	    // Учредительные документы
	    $Fdocs = FoundingDocument::model()
		    ->where('deleted', false)
		    ->where('id_yur',  $model->primaryKey)
		    ->where('type_yur', 'Организации')
		    ->findAll();

	    // получаем набор документов типа "Доверенность"
		$PAdocs = PowerAttorneysLE::model()
		    ->where('deleted', false)
		    ->where('id_yur', $model->primaryKey)
		    ->where('type_yur', 'Организации')
		    ->findAll();

        $freeDocs = FreeDocument::model()
		    ->where('deleted', false)
		    ->where('id_yur', $model->primaryKey)
		    ->findAll();

	    $this->render('documents/list', array(
            'freeDocs'  => $freeDocs,
            'Fdocs'     => $Fdocs,
            'PAdocs'    => $PAdocs
        ));
    }

// ╔═════════════════════════╗
// ║ Учредительные документы ║
// ╚═════════════════════════╝

	/**
	 * Создание учредительного документа
	 *
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionAdd_founding($id) {
		$this->cur_tab = 'documents';

		/** @var $model Organizations */
		$model = Organizations::model()->findByPk($id);
		if (!$model) throw new CHttpException(404);
		$this->organization = $model;

		$doc = new FoundingDocument();
		$doc->id_yur    = $id;
		$doc->type_yur  = "Организации";
		$doc->from_user = true;
		$doc->user      = SOAPModel::USER_NAME;
		$error = '';
		if ($_POST && !empty($_POST['FoundingDocument'])) {
			$doc->setAttributes($_POST['FoundingDocument']);
			if ($doc->validate()) {
				try {
					$doc->save();
					$this->redirect($this->createUrl('documents', array('id' => $id)));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}
		$this->render('documents/founding_form', array('doc' => $doc, 'error' => $error));
	}

	/**
	 * Редактирование учредительного документа
	 *
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionEdit_founding($id) {
		$this->cur_tab = 'documents';

		/** @var $doc FoundingDocument */
		$doc = FoundingDocument::model()->findByPk($id);
		if (!$doc) throw new CHttpException(404);
		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');

		/** @var $org Organizations */
		$org = Organizations::model()->findByPk($doc->id_yur);
		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
		$this->organization = $org;

		$doc->user      = SOAPModel::USER_NAME;
		$error = '';
		if ($_POST && !empty($_POST['FoundingDocument'])) {
			$doc->setAttributes($_POST['FoundingDocument']);
			if ($doc->validate()) {
				try {
					$doc->save();
					$this->redirect($this->createUrl('show_founding', array('id' => $id)));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}
		$this->render('documents/founding_form', array('doc' => $doc, 'error' => $error));
	}

	/**
	 * Просмотр учредительного документа
	 *
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionShow_founding($id) {
		$this->cur_tab = 'documents';

		/** @var $doc FoundingDocument */
		$doc = FoundingDocument::model()->findByPk($id);
		if (!$doc) throw new CHttpException(404);
		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');

		/** @var $org Organizations */
		$org = Organizations::model()->findByPk($doc->id_yur);
		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
		$this->organization = $org;

		$this->render('documents/founding_show', array('model' => $doc));
	}

	public function actionDelete_founding($id) {
		$this->cur_tab = 'documents';

		/** @var $doc FoundingDocument */
		$doc = FoundingDocument::model()->findByPk($id);
		if (!$doc) throw new CHttpException(404);
		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');

		/** @var $org Organizations */
		$org = Organizations::model()->findByPk($doc->id_yur);
		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
		$this->organization = $org;

		if (Yii::app()->request->isAjaxRequest) {
			$ret = array();
			try {
				$doc->delete();
			} catch (Exception $e) {
				$ret['error'] = $e->getMessage();
			}
			echo CJSON::encode($ret);
			Yii::app()->end();
		} else {
			if (isset($_POST['result'])) {
				switch ($_POST['result']) {
					case 'yes':
						if ($doc->delete()) {
							$this->redirect($this->createUrl('documents', array('id' => $this->organization->primaryKey)));
						} else {
							throw new CHttpException(500, 'Не удалось удалить учредительный документ');
						}
						break;
					default:
						$this->redirect($this->createUrl('show_founding', array('id' => $doc->primaryKey)));
						break;
				}
			}
			$this->render('delete_founding', array('model' => $doc));
		}
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
	public function aaaaaaaaaaaaaaaaaaaaaaactionEdit_founding($id) {
		$this->cur_tab = 'documents';

		/** @var $doc FoundingDocument */
		$doc = FoundingDocument::model()->findByPk($id);
		if (!$doc) throw new CHttpException(404);
		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');

		/** @var $org Organizations */
		$org = Organizations::model()->findByPk($doc->id_yur);
		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
		$this->organization = $org;

		$doc->user      = SOAPModel::USER_NAME;
		$error = '';
		if ($_POST && !empty($_POST['FoundingDocument'])) {
			$doc->setAttributes($_POST['FoundingDocument']);
			if ($doc->validate()) {
				try {
					$doc->save();
					$this->redirect($this->createUrl('show_founding', array('id' => $id)));
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}
		$this->render('documents/founding_form', array('doc' => $doc, 'error' => $error));
	}

	/**
	 * Просмотр учредительного документа
	 *
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function aaaaaaaaaaaaaaaaaaaaaaactionShow_founding($id) {
		$this->cur_tab = 'documents';

		/** @var $doc FoundingDocument */
		$doc = FoundingDocument::model()->findByPk($id);
		if (!$doc) throw new CHttpException(404);
		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');

		/** @var $org Organizations */
		$org = Organizations::model()->findByPk($doc->id_yur);
		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
		$this->organization = $org;

		$this->render('documents/founding_show', array('model' => $doc));
	}

	public function aaaaaaaaaaaaaaaaaaaaaaactionDelete_founding($id) {
		$this->cur_tab = 'documents';

		/** @var $doc FoundingDocument */
		$doc = FoundingDocument::model()->findByPk($id);
		if (!$doc) throw new CHttpException(404);
		if ($doc->type_yur != 'Организации') throw new CHttpException(404, 'У документа неверный тип для данной страницы');

		/** @var $org Organizations */
		$org = Organizations::model()->findByPk($doc->id_yur);
		if (!$org) throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
		$this->organization = $org;

		if (Yii::app()->request->isAjaxRequest) {
			$ret = array();
			try {
				$doc->delete();
			} catch (Exception $e) {
				$ret['error'] = $e->getMessage();
			}
			echo CJSON::encode($ret);
			Yii::app()->end();
		} else {
			if (isset($_POST['result'])) {
				switch ($_POST['result']) {
					case 'yes':
						if ($doc->delete()) {
							$this->redirect($this->createUrl('documents', array('id' => $this->organization->primaryKey)));
						} else {
							throw new CHttpException(500, 'Не удалось удалить учредительный документ');
						}
						break;
					default:
						$this->redirect($this->createUrl('show_founding', array('id' => $doc->primaryKey)));
						break;
				}
			}
			$this->render('delete_founding', array('model' => $doc));
		}
	}










	public function actionDocument_Add($yur_id, $doc_type, $id = false){
        if(empty($_POST['PowerAttorneysLE']))
        { // вгружаем форму
            $form_url_params = array();
            switch($doc_type){
                case 'pa':
                    if($id){
                        $model = PowerAttorneysLE::model()->findByPk($id);
                        $form_url_params['id'] = $id;
                    }
                    else{
                        $model = PowerAttorneysLE::model();
                    }

                    $individuals = Individuals::model()->findAll();
                    $individuals_arr = array();
                    foreach($individuals as $individual){
                        $individuals_arr[$individual->id] = $individual->name;
                    }

                    $this->render('documents/add_pa', array('model' => $model, 'individuals' => $individuals_arr, 'url_params' => $form_url_params));
                    break;
            }
        } else {
        // если POST не пустой, значит сохраняем форму и редиректим на страницу организации
			switch($doc_type){
                case 'pa':
                    /** @var $model PowerAttorneysLE */
	                $model = PowerAttorneysLE::model();
                    foreach($_POST['PowerAttorneysLE'] as $parameter => $value){
                        $model->$parameter = $value;
                    }
	                $model->scans = array();
	                $model->id_yur = $yur_id;
                    break;
            }
            if($id){
                $model->id = $id;
            }
            //CVarDumper::dump($model);
            $model->save();
	        // Вернемся к просмотру организации
            $this->redirect($this->createUrl('/legal/my_organizations/documents', array('id', $yur_id)));
            //$this->actionDocuments('000000003');
        }
    }

    public function actionDocument_show($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'documents';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/documents/show', array('id' => $id), true), 'model' => $model));
    }



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

    public function actionBenefits($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/benefits/list', array('id' => $id), true), 'model' => $model));
    }

    public function actionBenefit_add($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/benefits/add', array('id' => $id), true), 'model' => $model));
    }

    public function actionBenefit_show($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/benefits/show', array('id' => $id), true), 'model' => $model));
    }

    public function actionContracts($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/contracts/list', array('id' => $id), true), 'model' => $model));
    }

    public function actionContract_add($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/contracts/add', array('id' => $id), true), 'model' => $model));
    }

    public function actionContract_show($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/contracts/show', array('id' => $id), true), 'model' => $model));
    }

    public function actionMy_events($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'my_events';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/my_events/list', array('id' => $id), true), 'model' => $model));
    }

    // ╔═════════════════════╗
    // ║ Свободные документы ║
    // ╚═════════════════════╝

    /**
     *  @param  string  $action
     *  @param  int     $id
     *  @throws CHttpException
     */
    public function actionFree_document($action, $id) {
        $this->cur_tab = 'documents';

        if ($action == 'create'){
            $doc = new FreeDocument();
            $doc->id_yur    = $id;
            $doc->type_yur  = 'Организации';
        } else {
            $doc = FreeDocument::model()->findByPk($id);
            if (!$doc){
                throw new CHttpException(404, 'Не найден указанный документ.');
            }
        }
        $org = Organizations::model()->findByPk($doc->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено указанное юридическое лицо.');
        }
        $this->organization = $org;

        switch ($action){
            /** Action show */
            case 'show': {
                $this->menu_current = 'index';
                $this->render('show', array(
                    'content' => $this->renderPartial('documents/free_document/show',
                        array(
                            'id'        => $id,
                            'freeDoc'   => $doc
                        ), true),
                    'model'  => $doc
                ));
            } break;

            /** Action create */
            case 'create': {
                $error = '';
                if ($_POST && !empty($_POST['FreeDocument'])) {
                    $doc->setAttributes($_POST['FreeDocument']);
                    if ($doc->validate()) {
                        try {
                            $doc->save();
                            $this->redirect($this->createUrl('documents', array('id' => $id)));
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        }
                    }
                }
                $this->render('documents/free_document/form',
                    array(
                        'doc'   => $doc,
                        'error' => $error
                    )
                );
            } break;

            /** Action update */
            case 'update': {
                $error = '';
                if ($_POST && !empty($_POST['FreeDocument'])) {
                    $doc->setAttributes($_POST['FreeDocument']);
                    if ($doc->validate()) {
                        try {
                            $doc->save();
                            $this->redirect($this->createUrl('free_document', array('action' => 'show', 'id' => $id)));
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        }
                    }
                }
                $this->render('documents/free_document/form', array(
                    'doc'   => $doc,
                    'error' => $error
                ));
            } break;

            /** Action delete */
            case 'delete': {
                if (Yii::app()->request->isAjaxRequest) {
                    $ret = array();
                    try {
                        $doc->delete();
                    } catch (Exception $e) {
                        $ret['error'] = $e->getMessage();
                    }
                    echo CJSON::encode($ret);
                    Yii::app()->end();
                } else {
                    if (isset($_POST['result'])) {
                        switch ($_POST['result']) {
                            case 'yes':
                                if ($doc->delete()) {
                                    $this->redirect($this->createUrl('documents', array('id' => $this->organization->primaryKey)));
                                } else {
                                    throw new CHttpException(500, 'Не удалось удалить свободный документ');
                                }
                                break;
                            default:
                                $this->redirect($this->createUrl('free_document', array('action' => 'show', 'id' => $doc->primaryKey)));
                                break;
                        }
                    }
                    $this->render('documents/free_document/delete', array('model' => $doc));
                }
            } break;

            default: {
                throw new CHttpException(500, 'Указано неверное действие.');
            }
        }
    }

    // ╔══════════════╗
    // ║ Доверенности ║
    // ╚══════════════╝

    /**
     *  @param  string  $action
     *  @param  int     $id
     *
     *  @throws CHttpException
     */
    public function actionPower_attorney_le($action, $id) {
        $this->cur_tab = 'documents';

        if ($action == 'create'){
            $doc = new PowerAttorneysLE();
            $doc->id_yur    = $id;
//            $doc->type_yur  = 'Организации';
        } else {
            $doc = PowerAttorneysLE::model()->findByPk($id);
            if (!$doc){
                throw new CHttpException(404, 'Не найдена указаная доверенность.');
            }
        }
        $org = Organizations::model()->findByPk($doc->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено указанное юридическое лицо.');
        }
        $this->organization = $org;

        switch ($action){
            /** Action show */
            case 'show': {
                $this->render('show', array(
                    'content' => $this->renderPartial('documents/power_attorney_le/show',
                        array(
                            'id'    => $id,
                            'doc'   => $doc
                        ), true),
                    'model' => $doc
                ));
            } break;

            /** Action update */
            case 'update': {
                $error = '';
                if ($_POST && !empty($_POST['PowerAttorneysLE'])) {
                    $doc->setAttributes($_POST['PowerAttorneysLE']);
                    if ($doc->validate()) {
                        try {
                            $doc->save();
                            $this->redirect($this->createUrl('power_attorney_le', array('action' => 'show', 'id' => $id)));
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        }
                    }
                }
                $this->render('documents/power_attorney_le/form', array(
                    'doc'   => $doc,
                    'error' => $error
                ));
            } break;

            /** Action create */
            case 'create': {
                $error = '';
                if ($_POST && !empty($_POST['PowerAttorneysLE'])) {
                    $doc->setAttributes($_POST['PowerAttorneysLE']);
                    if ($doc->validate()) {
                        try {
                            $doc->save();
                            $this->redirect($this->createUrl('documents', array('id' => $id)));
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        }
                    }
                }
                $this->render('documents/power_attorney_le/form',
                    array(
                        'doc'   => $doc,
                        'error' => $error
                    )
                );
            } break;

            /** Action delete */
            case 'delete': {
                if (Yii::app()->request->isAjaxRequest) {
                    $ret = array();
                    try {
                        $doc->delete();
                    } catch (Exception $e) {
                        $ret['error'] = $e->getMessage();
                    }
                    echo CJSON::encode($ret);
                    Yii::app()->end();
                } else {
                    if (isset($_POST['result'])) {
                        switch ($_POST['result']) {
                            case 'yes':
                                if ($doc->delete()) {
                                    $this->redirect($this->createUrl('documents', array('id' => $this->organization->primaryKey)));
                                } else {
                                    throw new CHttpException(500, 'Не удалось удалить довереность.');
                                }
                                break;
                            default:
                                $this->redirect($this->createUrl('power_attorney_le', array('action' => 'show', 'id' => $doc->primaryKey)));
                                break;
                        }
                    }
                    $this->render('documents/power_attorney_le/delete', array('model' => $doc));
                }
            } break;

            default: {
                throw new CHttpException(500, 'Указано неверное действие.');
            }
        }
    }

    // ╔══════════════════╗
    // ║ Банковские счета ║
    // ╚══════════════════╝
    /**
     * Список банковских счетов для указзаного юр. лица $id.
     *
     * @param   int $id
     *
     * @throws  CHttpException
     */
    public function actionSettlements($id) {
        $this->cur_tab = 'settlements';

        $org = Organizations::model()->findByPk($id);
        if (!$org) {
            throw new CHttpException(404, 'Не найдено указанное юридическое лицо.');
        }
        $this->organization = $org;

        $accounts = SettlementAccount::model()
            ->where('deleted', false)
            ->where('id_yur', $org->primaryKey)
//            ->where('type_yur', 'Организации')
            ->findAll();

        $this->render('settlements/list', array('accounts' => $accounts));
    }

    /**
     *  Управление банковским счетом. В $action передается выполняемое действие.
     *  Если создается новый банковский счет, тогда в $id передается id_yur (идентификатор юр. лица),
     *  к которому привязывается счет. Во всех остальных случаях $id счета (атрибут id).
     *
     *  @param  string  $action
     *  @param  int     $id
     *
     *  @throws CHttpException
     */
    public function actionSettlement($action, $id) {
        $this->cur_tab = 'settlements';

        if ($action == 'create'){
            $acc = new SettlementAccount();
            $acc->id_yur = $id;
        } else {
            $acc = SettlementAccount::model()->findByPk($id);
            if (!$acc){
                throw new CHttpException(404, 'Не найден банковский счет.');
            }
        }
        $org = Organizations::model()->findByPk($acc->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено указанное юридическое лицо.');
        }
        $this->organization = $org;

        switch ($action){
            /** Action show */
            case 'show': {
                $acc->bank_name = SettlementAccount::getBankName($acc->bank);

                $this->render('show', array(
                    'content' => $this->renderPartial('settlements/show',
                        array(
                            'id'    => $id,
                            'acc'   => $acc
                        ), true),
                    'model' => $acc
                ));
            } break;

            /** Action update */
            case 'update': {
                $error = '';
                if ($_POST && !empty($_POST['SettlementAccount'])) {
                    $acc->setAttributes($_POST['SettlementAccount']);
                    $acc->str_managing_persons = $_POST['SettlementAccount']['str_managing_persons'];
                    $acc->managing_persons = CJSON::decode($acc->str_managing_persons);
                    if ($acc->validate()) {
                        try {
                            $acc->save();
                            $this->redirect($this->createUrl('settlement', array('action' => 'show', 'id' => $id)));
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        }
                    }
                }
                $acc->bank_name = SettlementAccount::getBankName($acc->bank);

                $this->render('settlements/form',
                    array(
                        'model' => $acc,
                        'error' => $error
                    )
                );
            } break;

            /** Action create */
            case 'create': {
                $error = '';
                if ($_POST && !empty($_POST['SettlementAccount'])) {
                    $acc->setAttributes($_POST['SettlementAccount']);
                    $acc->str_managing_persons = $_POST['SettlementAccount']['str_managing_persons'];
                    $acc->managing_persons = CJSON::decode($acc->str_managing_persons);
                    if ($acc->validate()) {
                        try {
                            $acc->save();
                            $this->redirect($this->createUrl('settlements', array('id' => $this->organization->primaryKey)));
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        }
                    }
                }
                $acc->bank_name = SettlementAccount::getBankName($acc->bank);

                $this->render('settlements/form',
                    array(
                        'model' => $acc,
                        'error' => $error
                    )
                );
            } break;

            /** Action delete */
            case 'delete': {
                if (Yii::app()->request->isAjaxRequest) {
                    $ret = array();
                    try {
                        $acc->delete();
                    } catch (Exception $e) {
                        $ret['error'] = $e->getMessage();
                    }
                    echo CJSON::encode($ret);
                    Yii::app()->end();
                } else {
                    if (isset($_POST['result'])) {
                        switch ($_POST['result']) {
                            case 'yes':
                                if ($acc->delete()) {
                                    $this->redirect($this->createUrl('settlements', array('id' => $this->organization->primaryKey)));
                                } else {
                                    throw new CHttpException(500, 'Не удалось удалить банковский счет.');
                                }
                            break;
                            default:
                                $this->redirect($this->createUrl('settlements', array('action' => 'show', 'id' => $acc->primaryKey)));
                            break;
                        }
                    }
                    $this->render('settlements/delete', array('model' => $acc));
                }
            } break;

            default: {
                throw new CHttpException(500, 'Указано неверное действие.');
            }
        }
    }
}