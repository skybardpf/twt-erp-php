<?php
/**
 * Список контрагентов (внешних организаций).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class IndexAction extends CAction
{
    /**
     * Список контрагентов (внешних организаций).
     * @throws CHttpException
     */
    public function run()
    {
        /**
         * @var $controller ContractorController
         */
        $controller = $this->controller;
        $controller->pageTitle .= 'Список контрагентов';

        $data = $controller->getDataProvider();
        $controller->render(
            'index',
            array(
                'data' => $data
            )
        );
    }
}