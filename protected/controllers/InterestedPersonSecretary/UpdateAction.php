<?php
/**
 * Редактирование "Секретаря".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование "Секретаря".
     * @param string $id        Идентификатор лица
     * @param string $type_lico Тип лица
     * @param string $id_yur    Идентификатор организации
     * @param string $type_yur  Тип организации
     * @param string $date      Дата
     * @param string $number_stake      Номер пакета акций
     * @throws CHttpException
     */
    public function run($id, $type_lico, $id_yur, $type_yur, $date, $number_stake)
    {
        /**
         * @var Interested_person_shareholderController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование секретаря';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($id_yur, $forceCached);
        /**
         * @var InterestedPersonSecretary $model
         */
        $model = InterestedPersonSecretary::model()->findByPk($id, $type_lico, $id_yur, $type_yur, $date, $number_stake, $forceCached);
        $model->individual_id = $model->organization_id = $model->contractor_id = $model->primaryKey;

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
            if ($model->validate()) {
                try {
                    $ret = $model->save($old_model);
                    if ($ret === null)
                        throw new CException('Ошибка при сохранении секретаря');

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
                    'content' => $controller->renderPartial('/interested_person_secretary/form',
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