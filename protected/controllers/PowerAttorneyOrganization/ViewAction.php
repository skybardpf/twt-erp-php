<?php
/**
 * Просмотр данных о доверенности организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр данных о доверенности организации.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var Power_attorney_organizationController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр доверенности';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $model = PowerAttorneyForOrganization::model()->findByPk($id, $force_cache);
        $org = Organization::model()->findByPk($model->id_yur, $force_cache);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/power_attorney_organization/view',
                array(
                    'model'         => $model,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}