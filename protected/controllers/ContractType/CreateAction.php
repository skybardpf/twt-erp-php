<?php
/**
 * Добавление вида договора
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    public function run()
    {
        /**
         * @var Contract_typeController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Добавление вида договора';

        $model = new ContractType();
        $model->forceCached = $controller->getForceCached();

        if(isset($_POST['ajax']) && $_POST['ajax']==='form-type-contract') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $model->setAttributes($data);
            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl('index'));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/contract_type/form',
            array(
                'model' => $model
            )
        );
    }
}