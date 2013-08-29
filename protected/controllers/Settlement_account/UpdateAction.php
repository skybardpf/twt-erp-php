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
         * @var Settlement_accountController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование банковского счета';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $model = SettlementAccount::model()->findByPk($id, $forceCached);

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-account') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $org = Organization::model()->findByPk($model->id_yur, $forceCached);
        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $model->setAttributes($data);
            $model->managing_persons = CJSON::decode($model->json_managing_persons);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('view', array('id' => $model->primaryKey)));
                } catch (SoapParseException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
            $model->correspondent_bank_name = Bank::model()->getName($model->correspondent_bank, $forceCached);
            $model->bank_name = Bank::model()->getName($model->bank);
        }
        $model->json_managing_persons = CJSON::encode($model->managing_persons);

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