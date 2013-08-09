<?php
/**
 * Создание доверенности для контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class CreateAction extends CAction
{
    /**
     * Создание доверенности для контрагента.
     * @param string $cid
     */
    public function run($cid)
    {
        /**
         * @var Power_attorney_contractorController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание доверенности';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $org = Contractor::loadModel($cid, $force_cache);
        $model = PowerAttorneyForContractor::model()->createModel($org->primaryKey);
        $model->setForceCached($force_cache);

        if(isset($_POST['ajax']) && $_POST['ajax'] === 'form-power-attorney') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $class = get_class($model);
        if (isset($_POST[$class])) {
            $model->setAttributes($_POST[$class]);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('index'));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/power_attorney_contractor/form',
            array(
                'model' => $model,
                'organization' => $org
            )
        );
    }
}