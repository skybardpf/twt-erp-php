<?php
/**
 * Добавление Физ.лица
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class CreateAction extends CAction
{
    /**
     * Добавление Физ.лица
     */
    public function run()
    {
        /**
         * @var IndividualsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Добавление физического лица';

        $model = $controller->createModel();
        $error = '';
        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('index'));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }
        $controller->render(
            'add',
            array(
                'model' => $model,
                'error' => $error
            )
        );
    }
}