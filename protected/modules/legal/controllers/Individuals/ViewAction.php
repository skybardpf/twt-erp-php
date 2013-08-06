<?php
/**
 * Просмотр физического лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр физического лица.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var IndividualsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр физического лица';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $model = Individuals::loadModel($id, $force_cache);
        $controller->render(
            'view',
            array(
                'model' => $model,
            )
        );
    }
}