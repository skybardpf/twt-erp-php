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

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Contractor::model()->loadModel($id, $force_cache);
        $model->setForceCached($force_cache);

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

            $signatory = array();
            $tmp = CJSON::decode($model->json_signatories);
            foreach ($tmp as $v){
                $signatory[] = $v;
            }
            $model->signatories = $signatory;

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