<?php
/**
 * Просмотр события
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Event::model()->findByPk($id, $force_cache);

        $controller->render(
            'view',
            array(
                'model' => $model,
            )
        );
    }
}