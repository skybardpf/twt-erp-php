<?php
/**
 * Просмотр банковского счета.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр банковского счета с идентификатором $id.
     * @param  string $id
     */
    public function run($id)
    {
        /**
         * @var Settlement_accountsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр банковского счета';

        $model = $controller->loadModel($id);
        $org = $controller->loadOrganization($model->id_yur);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/settlement_accounts/view',
                array(
                    'model'         => $model,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->menu_current,
        ));
    }
}