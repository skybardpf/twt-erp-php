<?php
/**
 * Удаление Физ.лица
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление Физ.лица
     * @param string $id Идентификатор Физ.лица
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var IndividualsController $controller
         */
        $controller = $this->controller;

        $model = Individuals::loadModel($id);

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                $model->delete();
            } catch (Exception $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        } else {
            if (isset($_POST['result'])) {
                $controller->pageTitle .= ' | Удаление физического лица';

                switch ($_POST['result']) {
                    case 'yes':
                        if ($model->delete()) {
                            $controller->redirect($controller->createUrl('index'));
                        } else {
                            throw new CHttpException(500, 'Не удалось удалить лицо');
                        }
                    break;
                    default:
                        $controller->redirect($controller->createUrl('view', array('id' => $model->id)));
                    break;
                }
            }
            $controller->render('delete', array('model' => $model));
        }
    }
}