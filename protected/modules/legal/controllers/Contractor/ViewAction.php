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
        $controller->current_tab_menu = $controller::TAB_MENU_INFO;

        $model = $controller->loadModel($id);

        $controller->render(
            '/contractor/menu_tabs',
            array(
                'content' => $controller->renderPartial('/contractor/tab_info',
                    array(
                        'model' => $model
                    ),
                    true
                ),
                'model' => $model,
            )
        );
    }
}