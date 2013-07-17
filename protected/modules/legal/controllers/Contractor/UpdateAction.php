<?php
/**
 * Редактирование контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование контрагента.
     * @param string $id       Идентификатор контрагента
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var $controller ContractorController
         */
        $controller = $this->controller;
        $controller->pageTitle .= 'Редактирование контрагента';

        $aa = GroupEntities::model()
//            ->where('id', '000000106')
            ->findAll();
//        var_dump($aa);die;

        /**
         * @var $model Contractor
         */
        $model = $controller->loadModel($id);

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $model->primaryKey,
                        )
                    ));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render(
            'form',
            array(
                'model' => $model
            )
        );
    }
}