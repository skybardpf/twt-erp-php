<?php
/**
 * Редактирование договора.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование договора.
     * @param string $id       Идентификатор договора
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var ContractController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование договора';

        /**
         * @var Contract $model
         */
        $model = $controller->loadModel($id);

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-contract') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        /**
         * @var Organizations $org
         */
        $org = $controller->loadOrganization($model->id_yur);

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);

            $model->signatory = CJSON::decode($model->json_signatory);
            $model->signatory_contr = CJSON::decode($model->json_signatory_contractor);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $model->primaryKey,
                        )
                    ));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        // TODO только для тестов. Потом убрать. Здесь должен быть массив. Сейчас строка.
        $model->signatory = array('0000000033', '0000000044');
        $model->signatory_contr = array('0000000038', '0000000054');

        $model->json_signatory = CJSON::encode($model->signatory);
        $model->json_signatory_contractor = CJSON::encode($model->signatory_contr);

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial('/contract/form',
                array(
                    'organization' => $org,
                    'model' => $model
                ), true),
            'organization' => $org,
            'cur_tab' => 'contract',
        ));
    }
}