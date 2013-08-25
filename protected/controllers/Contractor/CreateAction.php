<?php
/**
 * Создание контрагента.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $model = new Contractor();
        $model->forceCached = $force_cache;

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

        $signatory = array();
        foreach($model->signatories as $v){
            $signatory[$v['id'].'_'.$v['doc_id']] = $v;
        }
        $model->json_signatories = (empty($signatory)) ? '{}' : CJSON::encode($signatory);

        $controller->render('/contractor/form',
            array(
                'model' => $model
            )
        );
    }
}