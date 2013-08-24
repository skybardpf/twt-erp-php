<?php
/**
 * Создание доверенности для контрагента.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Создание доверенности для контрагента.
     * @param string $cid
     */
    public function run($cid)
    {
        /**
         * @var Power_attorney_contractorController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание доверенности';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $org = Contractor::loadModel($cid, $force_cache);
        $model = PowerAttorneyForContractor::model()->createModel($org->primaryKey);
        $model->setForceCached($force_cache);

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
                    $org->clearCache();
                    $controller->redirect($controller->createUrl('list', array('cid' => $model->id_yur)));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $model->json_exists_files = CJSON::encode(array());
        $model->json_exists_scans = CJSON::encode(array());

        $controller->render(
            '/contractor/menu_tabs',
            array(
                'content' => $controller->renderPartial('/power_attorney_contractor/form',
                    array(
                        'model' => $model,
                        'organization' => $org,
                    ),
                    true
                ),
                'model' => $org,
                'current_tab_menu' => 'power_attorney'
            )
        );
    }
}