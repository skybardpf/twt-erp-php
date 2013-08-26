<?php
/**
 * Просмотр данных о событии.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр данных о событии.
     * @param string $org_id        Идентификатор организации.
     * @param string $id            Идентификатор события.
     * @throws CHttpException
     */
    public function run($org_id, $id)
    {
        /**
         * @var Calendar_eventsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр события';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Event::model()->findByPk($id, $force_cache);
        $org = Organization::model()->findByPk($org_id, $force_cache);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/my_events/view',
                array(
                    'organization' => $org,
                    'model' => $model
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}