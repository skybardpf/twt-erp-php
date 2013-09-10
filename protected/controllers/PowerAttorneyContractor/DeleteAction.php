<?php
/**
 * Удаление доверенности контрагента.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление доверенности контрагента.
     * @param $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Power_attorney_contractorController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Удаление доверенности';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $model = PowerAttorneyForContractor::model()->findByPk($id, $force_cache);
        $org = Contractor::model()->findByPk($model->id_yur, $force_cache);

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
                        $controller->redirect($controller->createUrl('list', array('cid' => $org->primaryKey)));
                    } else {
                        throw new CHttpException(500, 'Не удалось удалить доверенность.');
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
            '/contractor/menu_tabs',
            array(
                'content' => $controller->renderPartial('/power_attorney_contractor/delete',
                    array(
                        'model' => $model,
                    ),
                    true
                ),
                'organization' => $org,
                'cur_tab' => 'power_attorney'
            )
        );
    }
}