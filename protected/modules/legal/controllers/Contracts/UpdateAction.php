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
         * @var $controller ContractsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование договора';

        $model = $controller->loadModel($id);
        $org = $controller->loadOrganization($model->id_yur);

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);

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

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial('/contracts/form',
                array(
                    'organization' => $org,
                    'model' => $model
                ), true),
            'organization' => $org,
            'cur_tab' => 'contracts',
        ));
    }
}