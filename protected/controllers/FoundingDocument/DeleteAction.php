<?php
/**
 * Удаление учредительного документа.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление учредительного документа.
     * @param $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Founding_documentController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Удаление учредительного документа';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $model = FoundingDocument::model()->loadModel($id, $force_cache);
        if ($model->type_yur != 'Организации') {
            throw new CHttpException(404, 'У документа неверный тип для данной страницы');
        }
        $model->user = SOAPModel::USER_NAME;
        $model->setForceCached($force_cache);
        $org = Organization::model()->findByPk($model->id_yur, $force_cache);

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
                        throw new CHttpException(500, 'Не удалось удалить учредительный документ.');
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
            'content' => $controller->renderPartial('/founding_document/delete',
                array(
                    'model' => $model,
                    'organization' => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}