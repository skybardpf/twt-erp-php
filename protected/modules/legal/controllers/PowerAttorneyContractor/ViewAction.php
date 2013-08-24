<?php
/**
 * Просмотр данных о доверенности контрагента.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр данных о доверенности контрагента.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var Power_attorney_contractorController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр доверенности';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = PowerAttorneyForContractor::model()->loadModel($id, $force_cache);
        $model->setForceCached($force_cache);
        $org = Contractor::loadModel($model->id_yur, $force_cache);

        $controller->render(
            '/contractor/menu_tabs',
            array(
                'content' => $controller->renderPartial('/power_attorney_contractor/view',
                    array(
                        'model' => $model,
                        'organization' => $org,
                    ),
                    true
                ),
                'model' => $org,
                'current_tab_menu' => 'power_attorney'
            )
        );
    }
}