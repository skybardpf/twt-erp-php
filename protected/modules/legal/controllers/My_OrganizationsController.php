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
        
        if(empty($_POST['Organizations'])){ // вгружаем форму
            $form_url_params = array();
            if($id){
                $model = Organizations::model()->findByPk($id);
                $form_url_params['id'] = $id;
            }
            else{
                $model = Organizations::model();
            }
            
            $countries = Organizations::model('Countries')->findAll();
            $countries_arr = array();
            foreach($countries as $key => $country){
                $countries_arr[$country->id] = $country->name;
            }
            
            $this->render('add', array('model' => $model, 'countries' => $countries_arr, 'url_params' => $form_url_params));
        }
        else{ // если POST не пустой, значит сохраняем форму и редиректим на страницу организации
            $organization = Organizations::model();
            
            //CVarDumper::dump($_POST);
            foreach($_POST['Organizations'] as $parameter => $value){
                $organization->$parameter = $value;
            }
            if($id){
                $organization->id = $id;
            }
            //CVarDumper::dump($organization);
            //CVarDumper::dump($organization->save());
            $new_id = $organization->save();
            
            $this->actionShow($new_id);
        }
    }
    
    public function actionEdit($id){
        $this->actionAdd($id);
    }
}