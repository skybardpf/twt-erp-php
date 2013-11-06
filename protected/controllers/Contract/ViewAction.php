<?php
/**
 * Просмотр данных о договоре.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр данных о договоре.
     * @param string $id       Идентификатор договора.
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var ContractController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр договора';

        $model = Contract::model()->findByPk($id, $controller->getForceCached());
        $org = Organization::model()->findByPk($model->contractor_id, $controller->getForceCached());
        $contractType = ContractType::model()->findByPk($model->additional_type_contract);
        $contractTemplates = ContractTemplate::model()->listNames($model->primaryKey);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/contract/view',
                array(
                    'organization' => $org,
                    'model' => $model,
                    'contractType' => $contractType,
                    'contractTemplates' => $contractTemplates,
                ), true),
            'organization' => $org,
            'cur_tab' => 'contract',
        ));
    }
}