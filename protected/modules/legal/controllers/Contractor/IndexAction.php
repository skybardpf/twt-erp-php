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
     */
    public function run()
    {
//        var_dump('aaa');die;
        /**
         * @var ContractorController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список контрагентов';

        $controller->render(
            'index',
            array(
            )
        );
    }
}