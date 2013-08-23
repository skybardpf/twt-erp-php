<?php
/**
 * Список событий
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class IndexAction extends CAction
{
    /**
     *  Список событий.
     */
    public function run()
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список событий';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $data = Event::model()->listModelsAllOrganization($force_cache);

        $controller->render(
            'index',
            array(
                'data' => $data,
                'force_cache' => $force_cache
            )
        );
    }
}