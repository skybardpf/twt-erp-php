<?php
/**
 * Удаление вида договора.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление вида договора.
     * @param string $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Contract_typeController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Удаление вида договора';

        $model = ContractType::model()->findByPk($id, $controller->getForceCached());
        if ($model->is_standart)
            throw new CHttpException(403, 'Нельзя удалить стандартный вид договора');

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
                        throw new CHttpException(500, 'Не удалось удалить вид договора.');
                    }
                    break;
                default:
                    $controller->redirect($controller->createUrl(
                        'view',
                        array('id' => $model->primaryKey,)
                    ));
                    break;
            }
        }
        $controller->render('/contract_type/delete',
            array(
                'model' => $model,
            )
        );
    }
}