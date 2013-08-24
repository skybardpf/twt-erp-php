<?php
/**
 * Список банковских счетов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    /**
     * Список банковских счетов.
     */
    public function run()
    {
        /**
         * @var Settlement_accountsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список счетов';

        $data = $controller->getDataProvider();
        $controller->render(
            'index',
            array(
                'data' => $data
            )
        );
    }
}