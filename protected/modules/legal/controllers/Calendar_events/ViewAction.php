<?php
/**
 * Просмотр данных о событии.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
         * @var $controller Calendar_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр события';

        $model = $controller->loadModel($id);
        $org = $controller->loadOrganization($org_id);

        $controller->render('/my_organizations/show', array(
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