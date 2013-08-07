<?php
/**
 * Просмотр организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр организации.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var OrganizationController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр организации';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = $controller->loadOrganization($id, $force_cache);
        $model->setForceCached($force_cache);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/organization/tab_info',
                array(
                    'model' => $model
                ), true),
            'organization' => $model,
            'cur_tab' => 'info',
        ));
    }
}