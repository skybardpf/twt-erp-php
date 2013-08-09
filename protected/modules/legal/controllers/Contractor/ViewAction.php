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

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Contractor::model()->loadModel($id, $force_cache);
        $model->setForceCached($force_cache);

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
                'current_tab_menu' => 'info'
            )
        );
    }
}