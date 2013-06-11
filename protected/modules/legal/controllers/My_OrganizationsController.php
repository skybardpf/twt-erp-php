<?php
/**
 * User: Forgon
 * Date: 23.04.13
 */
class My_OrganizationsController extends Controller {

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
		if (!$model) throw new CHttpException(404);
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
            $countries = $this->getCountriesList();
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
                $countries = $this->getCountriesList();
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
	    if (!$model) throw new CHttpException(404);
		$this->organization = $model;

	    // Учредительные документы
	    $Fdocs = FoundingDocument::model()
		    ->where('deleted', false)
		    ->where('id_yur',  $model->getprimaryKey())
		    ->where('type_yur', 'Организации')
		    ->findAll();

	    // получаем набор документов типа "Доверенность"
	    //$PA_models = PowerAttorneysLE::model()->where('deleted', false)->where('id_yur', $id)->findAll();
	    $PA_models = array();

	    $this->render('documents/list', array('fdocs' => $Fdocs, 'PA_models' => $PA_models));
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

    private function getCountriesList(){
        $countries = Organizations::model('Countries')->findAll();
        $countries_arr = array();
        foreach($countries as $key => $country){
            $countries_arr[$country->id] = $country->name;
        }
        return $countries_arr;
    }



    public function actionDocument_show($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'documents';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/documents/show', array('id' => $id), true), 'model' => $model));
    }

    public function actionSettlements($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'settlements';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/settlement/list', array('id' => $id), true), 'model' => $model));
    }

    public function actionSettlement_add($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'settlements';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/settlement/add', array('id' => $id), true), 'model' => $model));
    }

    public function actionSettlement_show($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'settlements';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/settlement/show', array('id' => $id), true), 'model' => $model));
    }

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



}