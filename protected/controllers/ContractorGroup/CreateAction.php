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
                $name = Yii::app()->request->getPost('name');
                if (!$name){
                    throw new CException('Не указано название группы.');
                }

                /**
                 * @var Contractor_groupController    $controller
                 */
                $controller = $this->controller;
                if ($id === 'root'){
                    $parent_id = "";
                } else {
                    $parent = $controller->loadModel($id);
                    $parent_id = $parent->primaryKey;
                }
                $model = $controller->createModel($parent_id);
                $model->name = $name;
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