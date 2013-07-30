<?php
/**
 * Список организаций.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class IndexAction extends CAction
{
    /**
     * Список организаций.
     */
    public function run()
    {
        /**
         * @var OrganizationController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список организаций';

        $data = $controller->getDataProvider();
        $controller->render(
            'index',
            array(
                'data' => $data
            )
        );
    }
}