<?php
/**
 * Удаление банковского счет.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление банковского счет.
     * @param string $id Идентификатор договора
     */
    public function run($id)
    {
        /**
         * @var Settlement_accountsController $controller
         */
        $controller = $this->controller;

        $model = $controller->loadModel($id);
        $org = $controller->loadOrganization($model->id_yur);

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
            $controller->pageTitle .= ' | Удаление банковского счета';

            switch ($_POST['result']) {
                case 'yes':
                    if ($model->delete()) {
                        $controller->redirect($controller->createUrl('list', array('org_id' => $model->id_yur)));
                    } else {
                        throw new CHttpException(500, 'Не удалось удалить банковский счет.');
                    }
                break;
                default:
                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $model->primaryKey,
                        )
                    ));
                break;
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/settlement_accounts/delete',
                array(
                    'model' => $model,
                    'organization' => $org
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => $controller->menu_current,
        ));
    }
}