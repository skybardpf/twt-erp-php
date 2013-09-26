<?php
/**
 * Удаление договора.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление договора.
     * @param string $id Идентификатор договора
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var $controller ContractController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Удаление договора';

        /**
         * @var $model Contract
         */
        $model = $controller->loadModel($id);

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
                        $controller->redirect($controller->createUrl('list', array('org_id' => $model->id_yur)));
                    } else {
                        throw new CHttpException(500, 'Не удалось удалить договор.');
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
        $controller->render(
            'delete',
            array(
                'model' => $model
            )
        );
    }
}