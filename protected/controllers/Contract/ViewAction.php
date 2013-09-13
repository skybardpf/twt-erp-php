<?php
/**
 * Просмотр данных о договоре.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр данных о договоре.
     * @param string $id       Идентификатор договора.
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var ContractController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр договора';

        $model = Contract::model()->findByPk($id, $controller->getForceCached());
        $org = Organization::model()->findByPk($model->id_yur, $controller->getForceCached());

        // TODO только для тестов. Потом убрать. Здесь должен быть массив. Сейчас строка.
        $model->organization_signatories = array('0000000033', '0000000044');
        $model->contractor_signatories = array('0000000038', '0000000054');

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/contract/view',
                array(
                    'organization' => $org,
                    'model' => $model
                ), true),
            'organization' => $org,
            'cur_tab' => 'contract',
        ));
    }
}