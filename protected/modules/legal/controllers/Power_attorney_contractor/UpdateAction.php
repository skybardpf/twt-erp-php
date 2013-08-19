<?php
/**
 * Создание доверенности для контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class UpdateAction extends CAction
{
    /**
     * Создание доверенности для контрагента.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var Power_attorney_contractorController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование доверенности';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $model = PowerAttorneyForContractor::model()->loadModel($id, $force_cache);

        $model->setForceCached($force_cache);
        $org = Contractor::loadModel($model->id_yur, $force_cache);

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

            $model->upload_scans  = CUploadedFile::getInstancesByName('upload_scans');
            $model->upload_files  = CUploadedFile::getInstancesByName('upload_files');

            if ($model->validate()) {
                try {
                    $model->save();
//                    $controller->redirect($controller->createUrl('view', array('id' => $model->primaryKey)));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage() . $e->getTraceAsString());
                }
            }
        }

        $model->json_exists_files = CJSON::encode($model->list_files);
        $model->json_exists_scans = CJSON::encode($model->list_scans);

        $controller->render('/power_attorney_contractor/form',
            array(
                'model' => $model,
                'organization' => $org
            )
        );
    }
}