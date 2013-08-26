<?php
/**
 * Удаление события
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление события.
     * @param  string $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Event::model()->findByPk($id, $force_cache);

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                if (!$model->made_by_user){
                    throw new CException('Нельзя удалить событие, созданное администратором.');
                }
                $model->delete();
            } catch (Exception $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        } else {
            $controller->pageTitle .= ' | Удаление события';

            if (!$model->made_by_user){
                throw new CHttpException(500, 'Нельзя удалить событие, созданное администратором.');
            }
            if (isset($_POST['result'])) {
                switch ($_POST['result']) {
                    case 'yes':
                        if ($model->delete()) {
                            $controller->redirect($controller->createUrl('index'));
                        } else {
                            throw new CHttpException(500, 'Не удалось удалить событие.');
                        }
                    break;
                    default:
                        $controller->redirect($controller->createUrl('index'));
                    break;
                }
            }
            $controller->render('/my_events/delete', array(
                'model' => $model
            ));
        }
    }
}