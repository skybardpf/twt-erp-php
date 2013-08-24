<?php
/**
 * Удаление организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление организации.
     * @param string $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var OrganizationController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Удаление организации';

        $model = $controller->loadOrganization($id);

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
                        throw new CHttpException(500, 'Не удалось удалить организацию.');
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
            'content' => $controller->renderPartial('/organization/delete',
                array(
                    'model' => $model,
                ), true),
            'organization' => $model,
            'cur_tab' => 'info',
        ));
    }
}