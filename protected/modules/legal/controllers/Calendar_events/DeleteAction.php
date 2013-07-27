<?php
/**
 * Удаление события.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление события с идентификатором $id.
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

        $model = $controller->loadModel($id);


        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                if (!$model->made_by_user){
                    throw new CException('Нельзя удалить событие, созданное администратором.');
                }
                $model->delete();
            } catch (CException $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        } else {
            $controller->pageTitle .= ' | Просмотр события';

            $org = $controller->loadOrganization($org_id);

            if (!$model->made_by_user){
                throw new CHttpException(500, 'Нельзя удалить событие, созданное администратором.');
            }
            if (isset($_POST['result'])) {
                switch ($_POST['result']) {
                    case 'yes':
                        if ($model->delete()) {
                            $controller->redirect($controller->createUrl("list", array("org_id" => $org->primaryKey, "id" => $model->primaryKey)));
                        } else {
                            throw new CHttpException(500, 'Не удалось удалить событие.');
                        }
                    break;
                    default:
                        $controller->redirect($controller->createUrl("view", array("org_id" => $org->primaryKey, "id" => $model->primaryKey)));
                    break;
                }
            }
            $controller->render('/my_organizations/show', array(
                'content' => $controller->renderPartial('/my_events/delete',
                    array(
                        'organization' => $org,
                        'model' => $model
                    ), true),
                'organization' => $org,
                'cur_tab' => $controller->current_tab,
            ));
        }
    }
}