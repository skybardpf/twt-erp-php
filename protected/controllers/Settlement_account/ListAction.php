<?php
/**
 * Список банковских счетов для указаной организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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
         * @var Settlement_accountController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список банковских счетов';

        $forceCache = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($org_id, $forceCache);
        $data = SettlementAccount::model()->listModelsByOrganization($org, $forceCache);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/settlement_account/list',
                array(
                    'organization' => $org,
                    'data' => $data,
                    'forceCache' => $forceCache
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->menu_current,
        ));
    }
}