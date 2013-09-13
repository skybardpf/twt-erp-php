<?php
/**
 * Редактирование договора.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование договора.
     * @param string $id       Идентификатор договора
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var ContractController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование договора';

        $model = Contract::model()->findByPk($id, $controller->getForceCached());
        $org = Organization::model()->findByPk($model->id_yur, $controller->getForceCached());

        $class_name = get_class($model);

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-contract') {
            $model->signatory = CJSON::decode($_POST[$class_name]['json_signatory']);
            $model->contractor_signatories = CJSON::decode($_POST[$class_name]['json_signatory_contractor']);

            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);

            $model->signatory = CJSON::decode($model->json_signatory);
            $model->contractor_signatories = CJSON::decode($model->json_signatory_contractor);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $model->primaryKey,
                        )
                    ));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        // TODO только для тестов. Потом убрать. Здесь должен быть массив. Сейчас строка.
        $model->organization_signatories = array('0000000033', '0000000044');
        $model->contractor_signatories = array('0000000038', '0000000054');

        $model->json_organization_signatories = CJSON::encode($model->organization_signatories);
        $model->json_contractor_signatories = CJSON::encode($model->contractor_signatories);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/contract/form',
                array(
                    'organization' => $org,
                    'model' => $model
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => 'contract',
        ));
    }
}