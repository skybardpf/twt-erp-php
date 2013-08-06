<?php
/**
 * Редактирование Физ.лица
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование Физ.лица
     * @param string $id       Идентификатор Физ.лица
     */
    public function run($id)
    {
        /**
         * @var IndividualsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование физического лица';

        $model = Individuals::loadModel($id);

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-individual') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('view', array('id' => $model->primaryKey)));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }
        $controller->render('form', array('model' => $model));
    }
}