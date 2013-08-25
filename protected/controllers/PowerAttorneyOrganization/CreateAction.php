<?php
/**
 * Создание доверенности для организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Создание доверенности для организации.
     * @param string $org_id
     */
    public function run($org_id)
    {
        /**
         * @var Power_attorney_organizationController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание доверенности';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $org = Organization::model()->findByPk($org_id, $force_cache);

        $model = new PowerAttorneyForOrganization();
        $model->forceCached = $force_cache;
        $model->id_yur = $org->primaryKey;

        if(isset($_POST['ajax']) && $_POST['ajax'] === 'form-power-attorney') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $class = get_class($model);
        if (isset($_POST[$class])) {
            $model->setAttributes($_POST[$class]);

            if ($model->validate('json_exists_files')){
                $model->list_files = CJSON::decode($model->json_exists_files);
            }
            if ($model->validate('json_exists_scans')){
                $model->list_scans = CJSON::decode($model->json_exists_scans);
            }
            $model->type_of_contract = CJSON::decode($model->json_type_of_contract);
            $model->type_of_contract = ($model->type_of_contract === null) ? array () : $model->type_of_contract;

            $model->upload_scans  = CUploadedFile::getInstancesByName('upload_scans');
            $model->upload_files  = CUploadedFile::getInstancesByName('upload_files');

            if ($model->validate()) {
                try {
                    $model->save();
                    $org->clearCache();
                    $controller->redirect($controller->createUrl('documents/list', array('org_id' => $model->id_yur)));
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