<?php
/**
 * Просмотр данных о контрагенте.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = Contractor::model()->findByPk($id, $force_cache);

        $controller->render(
            '/contractor/menu_tabs',
            array(
                'content' => $controller->renderPartial('/contractor/tab_info',
                    array(
                        'model' => $model
                    ),
                    true
                ),
                'organization' => $model,
                'cur_tab' => 'info'
            )
        );
    }
}