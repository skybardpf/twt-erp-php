<?php
/**
 * Редактирование "Руководителя".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование "Руководителя".
     * @param string $id                Идентификатор лица
     * @param string $type_lico         Тип лица
     * @param string $org_id            Идентификатор организации
     * @param string $org_type          Тип организации
     * @param string $date              Дата
     * @param string $number_stake      Номер пакета акций
     * @throws CHttpException
     */
    public function run($id, $type_lico, $org_id, $org_type, $date, $number_stake)
    {
        /**
         * @var Interested_person_leaderController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование номинального акционера';

        if ($org_type === MTypeOrganization::ORGANIZATION)
            $org = Organization::model()->findByPk($org_id, $controller->getForceCached());
        elseif ($org_type === MTypeOrganization::CONTRACTOR)
            $org = Contractor::model()->findByPk($org_id, $controller->getForceCached());
        else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        /**
         * @var InterestedPersonLeader $model
         */
        $model = InterestedPersonLeader::model()->findByPk($id, $type_lico, $org_id, $org_type, $date, $number_stake, $controller->getForceCached());
        $model->individual_id = $model->contractor_id = $model->primaryKey;
        $type_lico = $model->type_lico;
        if ($type_lico == MTypeInterestedPerson::INDIVIDUAL) {
            $model->setScenario('typeIndividual');
        } elseif ($type_lico == MTypeInterestedPerson::CONTRACTOR) {
            $model->setScenario('typeContractor');
        }

        if(isset($_POST['ajax']) && $_POST['ajax'] === 'form-person') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        /**
         * Сохраняем для правильной очистки кеша.
         */
        $old_model = clone $model;

        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $model->setAttributes($data);
            $model->type_lico = $type_lico;
            if ($model->validate()) {
                try {
                    $ret = $model->save($old_model);
                    if ($ret === null)
                        throw new CException('Ошибка при сохранении руководителя');

                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $ret['id'],
                            'type_lico' => $ret['type_lico'],
                            'org_id' => $ret['id_yur'],
                            'org_type' => $ret['type_yur'],
                            'date' => $ret['date'],
                            'number_stake' => $ret['number_stake'],
                        )
                    ));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'organization' => $org,
                    'menu_tab' => $model->pageTypePerson,
                    'content' => $controller->renderPartial('/interested_person_leader/form',
                        array(
                            'model' => $model,
                            'organization' => $org,
                        ), true
                    ),
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}