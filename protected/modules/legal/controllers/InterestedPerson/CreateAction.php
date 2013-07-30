<?php
/**
 * Создание Заинтересованного лица
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 */
class CreateAction extends CAction
{
    /**
     * Создание Заинтересованного лица
     * @param $id_yur   Идентификатор Юр.лица
     * @param $role     Роль
     *
     * @throws CHttpException
     */
    public function run($id_yur, $role)
    {
        /**
         * @var $controller Interested_personsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' - Создание заинтересованного лица';

        $roles = InterestedPerson::getRoles();
        if (!in_array($role, $roles)){
            throw new CHttpException(404, 'Указана неправильная роль.');
        }

        /**
         * @var $org Organization
         */
        $org = Organization::model()->findByPk('000000001');
        if ($org === null) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        /**
         * @var $model InterestedPerson
         */
        $model = $controller->createModel($id_yur, $role);

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl(
                        'index',
                        array(
                            'org_id' => $org->primaryKey,
                        )
                    ));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_persons/form',
                array(
                    'model' => $model,
                    'organization' => $org,
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}