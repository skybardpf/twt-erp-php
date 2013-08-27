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

        $force_cache = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($org_id, $force_cache);
        $data = $controller->getDataProviderForOrganization($org);

        $controller->render('/organization/show', array(
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