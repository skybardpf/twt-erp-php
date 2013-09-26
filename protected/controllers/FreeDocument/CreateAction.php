<?php
/**
 * Добавление свободного документа.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Добавление свободного документа.
     * @param string $org_id
     * @throws CHttpException
     */
    public function run($org_id)
    {
        /**
         * @var Free_documentController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Добавление документа';

        $org = Organization::model()->findByPk($org_id, $controller->getForceCached());
        $model = FreeDocument::model()->createModel($org);
        $model->forceCached = $controller->getForceCached();

        $class = get_class($model);
        if ($_POST && !empty($_POST[$class])) {
            $model->setAttributes($_POST[$class]);

            if ($model->validate('json_exists_files')){
                $model->list_files = CJSON::decode($model->json_exists_files);
            }
            if ($model->validate('json_exists_scans')){
                $model->list_scans = CJSON::decode($model->json_exists_scans);
            }

            $model->upload_scans = CUploadedFile::getInstancesByName('upload_scans');
            $model->upload_files = CUploadedFile::getInstancesByName('upload_files');

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
            'content' => $controller->renderPartial('/free_document/form',
                array(
                    'model' => $model,
                    'organization' => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}