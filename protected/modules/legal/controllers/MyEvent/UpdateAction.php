<?php
/**
 * Редактирование события
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class UpdateAction extends CAction
{
    /**
     *  Редактирование события с идентификатором $id.
     *
     *  @param  string $id
     *
     *  @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование события';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Event::model()->loadModel($id, $force_cache);
        $model->setForceCached($force_cache);
        if (!$model->made_by_user){
            throw new CHttpException(500, 'Нельзя редактировать событие, созданное администратором.');
        }

        if ($_POST && !empty($_POST['Event'])) {
            $model->setAttributes($_POST['Event']);

            $model->countries = CJSON::decode($model->json_countries);
            if ($model->validate('json_exists_files')){
                $model->list_files = CJSON::decode($model->json_exists_files);
            }
            $model->upload_files = CUploadedFile::getInstancesByName('upload_files');

            if ($model->validate()) {
                try {
                    $model->save();
                    $this->controller->redirect($this->controller->createUrl('view', array('id' => $model->primaryKey)));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        } else {
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
            $model->json_countries = CJSON::encode($model->countries);
        }
        $model->json_exists_files = CJSON::encode($model->list_files);

        $this->controller->render(
            'form',
            array(
                'model' => $model,
            )
        );
    }
}