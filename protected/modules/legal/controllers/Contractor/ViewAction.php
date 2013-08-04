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

//        $groups = ContractorGroup::model()->getIndexData();
//        var_dump($groups);die;

        $controller->render(
            'view',
            array(
                'model' => $model,
//                'groups' => $groups,
            )
        );
    }
}