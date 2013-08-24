<?php
/**
 * Список Физических лиц.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class IndexAction extends CAction
{
    /**
     * Список Физических лиц.
     */
    public function run()
    {
        /**
         * @var IndividualController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список физических лиц';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $data = Individual::model()->getData($force_cache);
        $controller->render(
            'index',
            array(
                'data' => $data,
                'force_cache' => $force_cache
            )
        );
    }
}