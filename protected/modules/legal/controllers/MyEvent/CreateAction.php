<?php
/**
 * Добавление события.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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

        $model = $controller->createModel();

        if ($_POST && !empty($_POST['Event'])) {
            $model->setAttributes($_POST['Event']);

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
        } else {
            $model->json_organizations = $model->json_contractors = $model->json_countries = CJSON::encode(array());
        }
        $controller->render(
            'form',
            array(
                'model' => $model,
            )
        );
    }
}