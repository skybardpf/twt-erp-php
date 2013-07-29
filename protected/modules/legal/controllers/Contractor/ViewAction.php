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
         * @var ContractorController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр контрагента';

        $model = $controller->loadModel($id);
        $controller->render(
            'view',
            array(
                'model' => $model
            )
        );
    }
}