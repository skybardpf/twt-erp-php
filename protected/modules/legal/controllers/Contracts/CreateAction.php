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
         * @var $controller ContractsController
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

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial('/contracts/form',
                array(
                    'organization' => $org,
                    'model' => $model
                ), true),
            'organization' => $org,
            'cur_tab' => 'contracts',
        ));
    }
}