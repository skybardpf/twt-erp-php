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

        $data = $controller->getDataProvider();
        $controller->render(
            'index',
            array(
                'data' => $data
            )
        );
    }
}