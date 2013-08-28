<?php
/**
 * Добавление нового банковского счета к указанному в $org_id организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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
         * @var Settlement_accountController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Добавление банковского счета';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($org_id, $forceCached);
        $model = new SettlementAccount();
        $model->id_yur = $org->primaryKey;

        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $model->setAttributes($data);
            $model->managing_persons = CJSON::decode($model->json_managing_persons);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('list', array('org_id' => $org->primaryKey)));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }

            $model->correspondent_bank_name = Bank::model()->getName($model->correspondent_bank, $forceCached);
            $model->bank_name = Bank::model()->getName($model->bank);
            $model->json_managing_persons = CJSON::encode($model->managing_persons);
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/settlement_account/form',
                array(
                    'model'         => $model,
                    'organization'  => $org,
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => $controller->menu_current,
        ));
    }
}