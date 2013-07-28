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
         * @var IndividualsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список физических лиц';

        $data = $controller->getDataProvider();
        $controller->render(
            'index',
            array(
                'data' => $data
            )
        );
    }
}