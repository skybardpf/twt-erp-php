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

        $class = get_class($model);
        $country_id = (isset($_POST[$class]) && isset($_POST[$class]['country']) ? $_POST[$class]['country'] : null);
        if (!is_null($country_id)){
            if ($country_id == $model::COUNTRY_RUSSIAN_ID){
                $model->setScenario('russianCountry');
            } else {
                $model->setScenario('foreignCountry');
            }
        }

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-organization') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if ($_POST && !empty($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);

            $signatory = array();
            $tmp = CJSON::decode($model->json_signatories);
            foreach ($tmp as $v){
                $signatory[] = $v;
            }
            $model->signatories = $signatory;

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('index'));
                } catch (CException $e) {
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