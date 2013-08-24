<?php
/**
 * Редактирование доверенности для организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование доверенности для организации.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var Power_attorney_organizationController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование доверенности';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        /**
         * @var PowerAttorneyForOrganization $model
         */
        $model = PowerAttorneyForOrganization::model()->loadModel($id, $force_cache);
        $model->setForceCached($force_cache);
        $org = Organization::loadModel($model->id_yur, $force_cache);

        $class = get_class($model);
//        if (isset($_POST[$class]) && isset($_POST[$class]['typ_doc']) && $_POST[$class]['typ_doc'] == $model::TYPE_DOC_GENERAL){
//
//        }

        if(isset($_POST['ajax']) && $_POST['ajax'] === 'form-power-attorney') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST[$class])) {
            $model->setAttributes($_POST[$class]);

            if ($model->validate('json_exists_files')){
                $model->list_files = CJSON::decode($model->json_exists_files);
            }
            if ($model->validate('json_exists_scans')){
                $model->list_scans = CJSON::decode($model->json_exists_scans);
            }
            if ($model->typ_doc == $model::TYPE_DOC_GENERAL){
                $model->type_of_contract = array();
            } else {
                $model->setScenario('typeDocNotGeneral');
                $model->type_of_contract = CJSON::decode($model->json_type_of_contract);
                $model->type_of_contract = ($model->type_of_contract === null) ? array () : $model->type_of_contract;
            }

            $model->upload_scans  = CUploadedFile::getInstancesByName('upload_scans');
            $model->upload_files  = CUploadedFile::getInstancesByName('upload_files');

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('view', array('id' => $model->primaryKey)));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }
        $model->json_exists_files = CJSON::encode($model->list_files);
        $model->json_exists_scans = CJSON::encode($model->list_scans);
        $model->json_type_of_contract = CJSON::encode($model->type_of_contract);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial(
                '/power_attorney_organization/form',
                array(
                    'model'         => $model,
                    'organization'  => $org
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}