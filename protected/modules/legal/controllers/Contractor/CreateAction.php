<?php
/**
 * Создание контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class CreateAction extends CAction
{
    /**
     * Создание контрагента.
     * @throws CHttpException
     */
    public function run()
    {
        /**
         * @var $controller ContractorController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание контрагента';

        /**
         * @var $model Contractor
         */
        $model = $controller->createModel();

        if (isset($_POST[get_class($model)])) {
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

        $controller->render(
            'form',
            array(
                'model' => $model
            )
        );
    }
}