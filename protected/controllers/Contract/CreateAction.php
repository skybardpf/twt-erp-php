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

        $org = Organization::model()->findByPk($org_id, $controller->getForceCached());
        $model = new Contract();
        $model->contractor_id = $org->primaryKey;

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('list', array('org_id' => $org->primaryKey)));
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
                ), true),
            'organization' => $org,
            'cur_tab' => 'contract',
        ));
    }
}