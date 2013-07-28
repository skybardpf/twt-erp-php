<?php
/**
 * Создание договора.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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

        /**
         * @var Organizations $org
         */
        $org = $controller->loadOrganization($org_id);
        /**
         * @var $model Contract
         */
        $model = $controller->createModel($org);

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
        $model->signatory = array('0000000033', '0000000044');
        $model->signatory_contr = array('0000000038', '0000000054');

        $model->json_signatory = CJSON::encode($model->signatory);
        $model->json_signatory_contractor = CJSON::encode($model->signatory_contr);

        $controller->render('/my_organizations/show', array(
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