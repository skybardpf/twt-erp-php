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

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $data = Organization::model()->getFullData($force_cache);
        $controller->render(
            'index',
            array(
                'data' => $data,
                'force_cache' => $force_cache,
            )
        );
    }
}