<?php
/**
 * Редактирование группы контрагента.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование группы контрагента.
     * @param string $id       Идентификатор группы
     */
    public function run($id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                if (!isset($_POST['name']) || empty($_POST['name'])){
                    throw new CException('Не указано название группы.');
                }
                $ret = array(
                    'success' => true
                );

                /**
                 * @var Contractor_groupController    $controller
                 */
                $controller = $this->controller;
                $model = $controller->loadModel($id);
                $model->name = $_POST['name'];
                $model->save();

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