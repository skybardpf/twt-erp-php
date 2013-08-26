<?php
/**
 * Добавление события.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Добавление события.
     */
    public function run()
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Добавить событие';

        $model = new Event();

        if ($_POST && !empty($_POST['Event'])) {
            $model->setAttributes($_POST['Event']);

            $model->list_countries = CJSON::decode($model->json_countries);
            if ($model->validate('json_exists_files')){
                $model->list_files = CJSON::decode($model->json_exists_files);
            }
            $model->upload_files = CUploadedFile::getInstancesByName('upload_files');
            $model->list_yur = $model->getStructureOrg();

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('index'));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $organizations = array();
        $contractors = array();
        foreach ($model->list_yur as $v){
            if ($v['type_yur'] == 'Организации'){
                $organizations[] = $v['id_yur'];
            } elseif ($v['type_yur'] == 'Контрагенты'){
                $contractors[] = $v['id_yur'];
            }
        }

        $model->json_organizations = CJSON::encode($organizations);
        $model->json_contractors = CJSON::encode($contractors);
        $model->json_countries = CJSON::encode($model->list_countries);
        $model->json_exists_files = CJSON::encode($model->list_files);

        $controller->render(
            'form',
            array(
                'model' => $model,
            )
        );
    }
}