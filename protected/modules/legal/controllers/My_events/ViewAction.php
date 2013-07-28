<?php
/**
 * Просмотр события
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр события с идентификатором $id.
     * @param  string $id
     */
    public function run($id)
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр события';

        $model = $controller->loadModel($id);

        $controller->render(
            'view',
            array(
                'model' => $model,
            )
        );
    }
}