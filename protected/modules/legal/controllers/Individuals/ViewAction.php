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

        $model = Individuals::loadModel($id);
        $controller->render(
            'view',
            array(
                'model' => $model,
            )
        );
    }
}