<?php
/**
 * Удаление свободного документа.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление свободного документа.
     * @param $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Free_documentController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Удаление документа';

        $model = FreeDocument::model()->loadModel($id, $controller->getForceCached());
        $org = Organization::model()->findByPk($model->id_yur, $controller->getForceCached());

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                $model->delete();
                $org->clearCache();
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
                        $org->clearCache();
                        $controller->redirect($controller->createUrl('documents/list', array('org_id' => $org->primaryKey)));
                    } else {
                        throw new CHttpException(500, 'Не удалось удалить документ.');
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
            'content' => $controller->renderPartial('/free_document/delete',
                array(
                    'model' => $model,
                    'organization' => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}