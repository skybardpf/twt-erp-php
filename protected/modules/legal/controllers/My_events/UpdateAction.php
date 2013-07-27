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
        $model = Event::model()->findByPk($id);
        if (!$model) {
            throw new CHttpException(404, 'Не найдено событие.');
        }
        if (!$model->made_by_user){
            throw new CHttpException(500, 'Нельзя редактировать событие, созданное администратором.');
        }

        if ($_POST && !empty($_POST['Event'])) {
            $model->setAttributes($_POST['Event']);
//            var_dump($model->json_countries);die;

            $model->upload_files  = CUploadedFile::getInstancesByName('upload_files');
            $model->list_yur = $model->getStructureOrg();
            $model->countries = CJSON::decode($model->json_countries);

            if ($model->validate()) {
                try {


                    $model->save();
                    $this->controller->redirect($this->controller->createUrl('view', array('id' => $model->primaryKey)));
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

        $this->controller->render(
            'form',
            array(
                'model' => $model,
            )
        );
    }
}