<?php
/**
 * Редактирование вида договора
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
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
        $controller->pageTitle .= ' | Редактирование вида договора';

        $model = ContractType::model()->findByPk($id, $controller->getForceCached());
        if ($model->is_standard)
            throw new CHttpException(403, 'Нельзя редактировать стандартный вид договора');

        $controller->render('/contract_type/form',
            array(
                'model' => $model
            )
        );
    }
}