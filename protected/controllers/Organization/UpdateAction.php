<?php
/**
 * Редактирование организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование организации.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var OrganizationController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование организации';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Organization::model()->findByPk($id, $force_cache);

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

        if ($_POST && !empty($_POST[$class])) {
            $model->setAttributes($_POST[$class]);

            $signatory = array();
            $tmp = CJSON::decode($model->json_signatories);
            foreach ($tmp as $v){
                $signatory[] = $v;
            }
            $model->signatories = $signatory;

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('view', array('id' => $id)));
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