<?php
/**
 * Список банковских счетов для указаной организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ListAction extends CAction
{
    /**
     * Список банковских счетов для указаной организации.
     * @param string $org_id
     */
    public function run($org_id)
    {
        /**
         * @var Settlement_accountsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список банковских счетов';

        $org = $controller->loadOrganization($org_id);
        $data = $controller->getDataProviderForOrganization($org);

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial('/settlement_accounts/list',
                array(
                    'organization' => $org,
                    'data' => $data
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->menu_current,
        ));
    }
}