<?php
/**
 * Просмотр данных о договоре.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
         * @var $controller ContractsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр договора';

        $model = $controller->loadModel($id);
        $org = $controller->loadOrganization($model->id_yur);

        // TODO только для тестов. Потом убрать. Здесь должен быть массив. Сейчас строка.
        $model->signatory = array('0000000033', '0000000044');
        $model->signatory_contr = array('0000000038', '0000000054');

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial('/contracts/view',
                array(
                    'organization' => $org,
                    'model' => $model
                ), true),
            'organization' => $org,
            'cur_tab' => 'contracts',
        ));
    }
}