<?php
/**
 * Список договоров для указаной организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ListAction extends CAction
{
    /**
     * Список договоров для указаной в $org_id организации.
     * @param string $org_id
     * @throws CHttpException
     */
    public function run($org_id)
    {
        /**
         * @var $controller ContractController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список договоров';

        $org = $controller->loadOrganization($org_id);
        $data = $controller->getDataProvider($org);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/contract/list',
                array(
                    'organization' => $org,
                    'data' => $data
                ), true),
            'organization' => $org,
            'cur_tab' => 'contract',
        ));
    }
}