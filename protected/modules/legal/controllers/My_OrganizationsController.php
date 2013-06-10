<?php
/**
 * User: Forgon
 * Date: 23.04.13
 */
class My_OrganizationsController extends Controller {

	public $layout = 'inner';
	public $menu_current = 'legal';
	public $cur_tab = '';

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
	 */
	public function actionShow($id) {
		$this->cur_tab      = 'info';
		$model = Organizations::model()->findByPk($id);
		$this->render('show', array('tab_content' => $this->renderPartial('tab_info', array('model' => $model), true), 'model' => $model));
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

    public function actionDocuments($id){
        // получаем набор документов типа "Доверенность"
        $PA_models = PowerAttorneysLE::model()->where('deleted', false)->where('id_yur', $id)->findAll();
        $model = Organizations::model()->findByPk($id);
        $this->cur_tab = 'documents';
        $this->render('show', array('tab_content' => $this->renderPartial('documents/list', array('yur_id' => $id, 'PA_models' => $PA_models, 'id' => $id), true), 'model' => $model));
        //$this->render('documents/list', array('yur_id' => $id, 'PA_models' => $PA_models));
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