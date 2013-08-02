<?php
/**
 * Удаление контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление контрагента.
     * @param $id       Идентификатор контрагента
     *
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Contractor_groupController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Удаление группы контрагента';

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
                        $controller->redirect($controller->createUrl('index'));
                    } else {
                        throw new CHttpException(500, 'Не удалось удалить группу контрагента.');
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
        $controller->render('delete', array('model' => $model));
    }
}