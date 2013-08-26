<?php
/**
 * Создание группы контрагента.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Создание группы контрагента.
     * @param string $id       Идентификатор родительской группы
     * @throws CHttpException
     */
    public function run($id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                if (!isset($_POST['name']) || empty($_POST['name'])){
                    throw new CException('Не указано название группы.');
                }

                /**
                 * @var Contractor_groupController    $controller
                 */
                $controller = $this->controller;
                $parent = $controller->loadModel($id);

                $model = $controller->createModel($parent);
                $model->name = $_POST['name'];
                $id = $model->save();

                $ret = array(
                    'success' => true,
                    'id' => $id,
                    'name' => $model->name
                );

            } catch (CException $e) {
                $ret = array(
                    'success' => false,
                    'error' => $e->getMessage(),
                );
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        }
    }
}