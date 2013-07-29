<?php
/**
 * Удаление Заинтересованного лица
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 */

class DeleteAction extends CAction
{
    /**
     * Удаление Заинтересованного лица
     * @param $id       Идентификатор физ.лица
     * @param $id_yur   Идентификатор Юр.лица
     * @param $role     Роль
     *
     * @throws CHttpException
     */
    public function run($id, $id_yur, $role)
    {
        /**
         * @var $controller Interested_personsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' - Удаление заинтересованного лица';

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
        $model = $controller->loadModel($id, $id_yur, $role);

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                $model->delete();
            } catch (Exception $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        }
        if (isset($_POST['result'])) {
            switch ($_POST['result']) {
                case 'yes':
                    if ($model->delete()) {
                        $controller->redirect($controller->createUrl('index'));
                    } else {
                        throw new CHttpException(500, 'Не удалось удалить заинтересованное лицо.');
                    }
                break;
                default:
                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $model->primaryKey,
                            'id_yur' => $model->id_yur,
                            'role' => $model->role,
                        )
                    ));
                break;
            }
        }
        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_persons/delete',
                array(
                    'model' => $model,
                    'organization' => $org,
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}