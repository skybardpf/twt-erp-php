<?php
/**
 * Список договоров для указаной организации.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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
         * @var ContractController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список договоров';

        $org = Organization::model()->findByPk($org_id, $controller->getForceCached());
        $data = Contract::model()->listModels($org->primaryKey, $controller->getForceCached());

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