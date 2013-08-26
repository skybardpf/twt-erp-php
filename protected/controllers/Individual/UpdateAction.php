<?php
/**
 * Редактирование Физ.лица
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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
         * @var IndividualController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование физического лица';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Individual::model()->findByPk($id, $force_cache);

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
        $controller->render(
            'form',
            array(
                'model' => $model,
                'force_cache' => $force_cache
            )
        );
    }
}