<?php
/**
 * Создание договора.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Создание договора.
     * @param string $org_id
     * @throws CHttpException
     */
    public function run($org_id)
    {
        /**
         * @var $controller ContractController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание договора';

        $contractTypeId = Yii::app()->request->getQuery('ctid');
        $org = Organization::model()->findByPk($org_id, $controller->getForceCached());
        $model = new Contract();
        $model->contractor_id = $org->primaryKey;

        if (empty($contractTypeId)){
            $contractType = new ContractType();
        } else {
            $model->additional_type_contract = $contractTypeId;
            $contractType = ContractType::model()->findByPk($model->additional_type_contract);
        }
        $model->makeRules($contractType);

        $class_name = get_class($model);

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-contract') {
            $model->organization_signatories = CJSON::decode($_POST[$class_name]['json_organization_signatories']);
            $model->contractor_signatories = CJSON::decode($_POST[$class_name]['json_contractor_signatories']);

            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $data = Yii::app()->request->getPost($class_name);
        if ($data) {
            $model->setAttributes($data);

            $json = CJSON::decode($model->json_organization_signatories);
            if ($json !== null)
                $model->organization_signatories = $json;
            $json = CJSON::decode($model->json_contractor_signatories);
            if ($json !== null)
                $model->contractor_signatories = $json;

            if ($model->validate('json_exists_documents'))
                $model->list_documents = CJSON::decode($model->json_exists_documents);
            if ($model->validate('json_exists_scans'))
                $model->list_scans = CJSON::decode($model->json_exists_scans);

            $model->upload_scans = CUploadedFile::getInstancesByName('upload_scans');
            $model->upload_documents = CUploadedFile::getInstancesByName('upload_documents');

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('list', array('org_id' => $org->primaryKey)));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $model->json_organization_signatories = CJSON::encode($model->organization_signatories);
        $model->json_contractor_signatories = CJSON::encode($model->contractor_signatories);
        $model->json_exists_documents = CJSON::encode($model->list_documents);
        $model->json_exists_scans = CJSON::encode($model->list_scans);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/contract/form',
                array(
                    'organization' => $org,
                    'model' => $model,
                    'contractType' => $contractType,
                    'action' => 'add',
                ), true),
            'organization' => $org,
            'cur_tab' => 'contract',
        ));
    }
}