<?php
/**
 * Добавление нового банковского счета к указанному в $org_id организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class CreateAction extends CAction
{
    /**
     * Добавление нового банковского счета к указанному в $org_id организации.
     * @param string $org_id       Идентификатор организации.
     */
    public function run($org_id)
    {
        /**
         * @var Settlement_accountsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Добавление банковского счета';

        $org = $controller->loadOrganization($org_id);
        $model = $controller->createModel($org);

        $error = '';
        if ($_POST && !empty($_POST['SettlementAccount'])) {
            $model->setAttributes($_POST['SettlementAccount']);
            $model->str_managing_persons = $_POST['SettlementAccount']['str_managing_persons'];
            $model->managing_persons = CJSON::decode($model->str_managing_persons);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('list', array('org_id' => $org->primaryKey)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
            $model->bank_name = SettlementAccount::getBankName($model->bank);
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/settlement_accounts/form',
                array(
                    'model'         => $model,
                    'organization'  => $org,
                    'error'         => $error
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => $controller->menu_current,
        ));
    }
}