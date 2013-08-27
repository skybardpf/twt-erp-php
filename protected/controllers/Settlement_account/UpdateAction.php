<?php
/**
 * Редактирование банковского счета.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование банковского счета.
     * @param string $id       Идентификатор банковского счета.
     */
    public function run($id)
    {
        /**
         * @var Settlement_accountsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование банковского счета';

        $model = $controller->loadModel($id);
        $org = $controller->loadOrganization($model->id_yur);

        $error = '';
        if ($_POST && !empty($_POST['SettlementAccount'])) {
            $model->setAttributes($_POST['SettlementAccount']);
            $model->str_managing_persons = $_POST['SettlementAccount']['str_managing_persons'];
            $model->managing_persons = CJSON::decode($model->str_managing_persons);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('view', array('id' => $model->primaryKey)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }

        } else {
            $model->bank = ((int)$model->bank_bik > 0) ? $model->bank_bik : (!empty($model->bank_swift) ? $model->bank_swift : '');
        }
        $model->bank_name = SettlementAccount::getBankName($model->bank);

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