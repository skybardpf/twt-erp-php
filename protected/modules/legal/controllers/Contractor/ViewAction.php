<?php
/**
 * Просмотр данных о контрагенте.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр данных о контрагенте.
     * @param string $id       Идентификатор контрагента.
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var $controller ContractorController
         */
        $controller = $this->controller;
        $controller->pageTitle .= 'Просмотр контрагента';

        /**
         * @var $model Contractor
         */
        $model = $controller->loadModel($id);
        $controller->render(
            'view',
            array(
                'model' => $model
            )
        );
    }
}