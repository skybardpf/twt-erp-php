<?php
/**
 * Создание "Секретаря".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Создание "Секретаря".
     * @param string $org_id    Идентификатор организации
     * @param string $org_type  Тип организации
     * @throws CHttpException
     */
    public function run($org_id, $org_type)
    {
        /**
         * @var Interested_person_secretaryController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание секретаря';

        $org = Organization::model()->findByPk($org_id, $controller->getForceCached());

        $model = new InterestedPersonSecretary();
        $model->id_yur = $org->primaryKey;
        $model->type_yur = MTypeOrganization::ORGANIZATION;
        $model->forceCached = $controller->getForceCached();

        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $model->setAttributes($data);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl(
                        'interested_person/index',
                        array(
                            'org_id' => $org->primaryKey,
                            'type' => $model->pageTypePerson
                        )
                    ));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person_secretary/form',
                array(
                    'model' => $model,
                    'organization' => $org,
                ), true
            ),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}