<?php
/**
 * Редактирование контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование контрагента.
     * @param string $id       Идентификатор контрагента
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var ContractorController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование контрагента';

        $model = $controller->loadModel($id);

        $class = get_class($model);
        $country_id = (isset($_POST[$class]) && isset($_POST[$class]['country']) ? $_POST[$class]['country'] : null);
        if (!is_null($country_id)){
            if ($country_id == $model::COUNTRY_RUSSIAN_ID){
                $model->setScenario('russianCountry');
            } else {
                $model->setScenario('foreignCountry');
            }
        }

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-contractor') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

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
                } catch (CException $e) {
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