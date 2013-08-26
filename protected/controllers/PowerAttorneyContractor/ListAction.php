<?php
/**
 * Список доверенностей контрагента.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ListAction extends CAction
{
    /**
     * Список доверенностей контрагента.
     * @param string $cid
     */
    public function run($cid)
    {
        /**
         * @var Power_attorney_contractorController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список доверенностей';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $org = Contractor::model()->findByPk($cid, $force_cache);
        $data = PowerAttorneyForContractor::model()->listModels($org->primaryKey, $force_cache);

        $controller->render(
            '/contractor/menu_tabs',
            array(
                'content' => $controller->renderPartial('/power_attorney_contractor/list',
                    array(
                        'data' => $data,
                        'model' => $org,
                    ),
                    true
                ),
                'model' => $org,
                'current_tab_menu' => 'power_attorney'
            )
        );
    }
}