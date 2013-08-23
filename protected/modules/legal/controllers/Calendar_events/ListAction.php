<?php
/**
 * Список текущих событий для организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ListAction extends CAction
{
    /**
     * Список текущих событий для организации.
     * @param string $org_id
     * @throws CHttpException
     */
    public function run($org_id)
    {
        /**
         * @var $controller Calendar_eventsController
         */
        $controller = $this->controller;

        if (isset($_GET['year']) && $_GET['year'] = 1){
            $filter = 'year';
            $title = 'События на год вперед';
            $controller->pageTitle .= ' | События на год вперед';
        } elseif (isset($_GET['ten']) && $_GET['ten'] = 1){
            $filter = 'ten';
            $title = 'Ближайщие 10 событий';
            $controller->pageTitle .= ' | Ближайщие 10 событий';
        } else {
            $filter = 'all';
            $title = 'Все события';
            $controller->pageTitle .= ' | Все события';
        }

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $org = Organization::loadModel($org_id, $force_cache);

        // TODO пока $force_cache = true
        $force_cache = true;
        $data = Event::model()->listModelsByOrg($org->primaryKey, $filter, $force_cache);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/my_events/list',
                array(
                    'organization' => $org,
                    'data' => $data,
                    'title' => $title,
                    'force_cache' => $force_cache
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}