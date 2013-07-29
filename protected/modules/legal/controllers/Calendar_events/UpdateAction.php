<?php
/**
 * Редактирование события
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование события с идентификатором $id.
     *
     * @param string $org_id        Идентификатор организации.
     * @param string $id            Идентификатор события.
     * @throws CHttpException
     */
    public function run($org_id, $id)
    {
        /**
         * @var $controller Calendar_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование события';

        $model = $controller->loadModel($id);
        if (!$model->made_by_user){
            throw new CHttpException(500, 'Нельзя редактировать событие, созданное администратором.');
        }

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-my-events') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $org = $controller->loadOrganization($org_id);

        if ($_POST && !empty($_POST['Event'])) {
            $model->setAttributes($_POST['Event']);

            $model->upload_files  = CUploadedFile::getInstancesByName('upload_files');
            $model->list_yur = $model->getStructureOrg();
            $model->countries = CJSON::decode($model->json_countries);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('view', array("org_id" => $org->primaryKey, "id" => $model->primaryKey)));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        } else {
            $list = array();
            if (isset($model->list_yur[0]) && is_array($model->list_yur[0])){
                for ($i = 0, $l=count($model->list_yur[0])/2; $i<$l; $i++){
                    $type = 'type_yur'.$i;
                    $id = 'id_yur'.$i;
                    if ($model->list_yur[0][$type] == 'Организации'){
                        $list[] = array(
                            'id_yur' => $model->list_yur[0][$id],
                            'type_yur' => 'Организации'
                        );
                    } elseif($model->list_yur[0][$type] == 'Контрагенты'){
                        $list[] = array(
                            'id_yur' => $model->list_yur[0][$id],
                            'type_yur' => 'Контрагенты'
                        );
                    }
                }
            }
            $model->list_yur = $list;

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

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial('/my_events/form',
                array(
                    'organization' => $org,
                    'model' => $model
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}