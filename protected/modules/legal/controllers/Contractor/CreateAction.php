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
         * @var ContractorController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание контрагента';

        $model = $controller->createModel();

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-contractor') {
            $class = get_class($model);
            $country_id = (isset($_POST[$class]) && isset($_POST[$class]['country']) ? $_POST[$class]['country'] : null);
            if (!is_null($country_id)){
                if ($country_id == $model::COUNTRY_RUSSIAN_ID){
                    $model->setScenario('russianCountry');
                } else {
                    $model->setScenario('foreignCountry');
                }
            }
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('index'));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $signatory = array();
        foreach($model->signatories as $v){
            $signatory[$v['id'].'_'.$v['doc_id']] = $v;
        }
        $model->json_signatories = (empty($signatory)) ? '{}' : CJSON::encode($signatory);

        $controller->render(
            'form',
            array(
                'model' => $model
            )
        );
    }
}