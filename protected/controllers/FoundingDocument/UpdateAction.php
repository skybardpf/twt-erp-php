<?php
/**
 * Редактирование данных об учредительном документе.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование данных об учредительном документе.
     * @param string $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Founding_documentController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование учредительного документа';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $model = FoundingDocument::model()->loadModel($id, $force_cache);
        if ($model->type_yur != 'Организации') {
            throw new CHttpException(404, 'У документа неверный тип для данной страницы');
        }
//        $model->user = SOAPModel::USER_NAME;
        $model->user = Yii::app()->user->getId();
        $model->setForceCached($force_cache);
        $org = Organization::model()->findByPk($model->id_yur, $force_cache);

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
                    $controller->redirect($controller->createUrl('view', array('id' => $id)));
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