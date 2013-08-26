<?php
/**
 * Добавление Физ.лица
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Добавление Физ.лица
     */
    public function run()
    {
        /**
         * @var IndividualController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Добавление физического лица';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $model = new Individual();
        $model->forceCached = $force_cache;

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-individual') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

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