<?php
/**
 * Удаление банковского счета.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление банковского счета.
     * @param string $id Идентификатор договора
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Settlement_accountController $controller
         */
        $controller = $this->controller;

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $model = SettlementAccount::model()->findByPk($id, $forceCached);
        $org = Organization::model()->findByPk($model->id_yur, $forceCached);

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
            'content' => $controller->renderPartial('/settlement_account/delete',
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