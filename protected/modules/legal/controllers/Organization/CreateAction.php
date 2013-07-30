<?php
/**
 * Добавление организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class CreateAction extends CAction
{
    /**
     * Добавление организации.
     */
    public function run()
    {
        /**
         * @var OrganizationController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Добавление организации';

        $model = $controller->createModel();

        if ($_POST && !empty($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('index'));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/organization/form',
                array(
                    'model' => $model,
                ), true),
            'organization' => $model,
            'cur_tab' => 'info',
        ));
    }
}