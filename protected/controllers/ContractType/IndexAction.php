<?php
/**
 * Список видов договоров
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    public function run()
    {
        /**
         * @var Contract_typeController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список видов договоров';

        $data = ContractType::model()->listModels($controller->getForceCached());
        $controller->render('/contract_type/index',
            array(
                'data' => $data
            )
        );
    }
}