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
        $controller->pageTitle .= 'Текущие события';

        $org = $controller->loadOrganization($org_id);

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial('/my_events/list',
                array(
                    'organization' => $org
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}