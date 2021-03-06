<?php
/**
 * Список банковских счетов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class IndexAction extends CAction
{
    /**
     * Список банковских счетов.
     * @param string $org_id
     */
    public function run($org_id)
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