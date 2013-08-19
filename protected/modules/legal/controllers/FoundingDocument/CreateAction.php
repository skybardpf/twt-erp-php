<?php
/**
 * Создание нового учредительного документа.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class CreateAction extends CAction
{
    /**
     * Создание нового учредительного документа.
     * @param string $id
     * @throws CHttpException
     */
    public function run($org_id)
    {
        /**
         * @var Founding_documentController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание учредительного документа';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $org = Organization::loadModel($org_id, $force_cache);

        $model = new FoundingDocument();
        $model->id_yur    = $org->primaryKey;
        $model->type_yur  = "Организации";
        $model->from_user = true;
        $model->user      = SOAPModel::USER_NAME;
        $model->list_files = array();
        $model->list_scans = array();

        $class = get_class($model);
        if ($_POST && !empty($_POST[$class])) {
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
                    $controller->redirect($controller->createUrl('documents/list', array('org_id' => $org_id)));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $model->json_exists_files = CJSON::encode($model->list_files);
        $model->json_exists_scans = CJSON::encode($model->list_scans);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/founding_document/form',
                array(
                    'model' => $model,
                    'organization' => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}