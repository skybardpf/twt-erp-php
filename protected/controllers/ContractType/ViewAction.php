<?php
/**
 * Просмотр вида договора
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * @param string $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Contract_typeController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр вида договора';

        $model = ContractType::model()->findByPk($id, $controller->getForceCached());
        $controller->render('/contract_type/view',
            array(
                'model' => $model
            )
        );
    }
}