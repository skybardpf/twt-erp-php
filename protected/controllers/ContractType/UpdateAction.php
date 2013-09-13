<?php
/**
 * Редактирование вида договора
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * @param string $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Contract_typeController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Редактирование вида договора';

        $model = ContractType::model()->findByPk($id, $controller->getForceCached());
        if ($model->is_standart)
            throw new CHttpException(403, 'Нельзя редактировать стандартный вид договора');

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