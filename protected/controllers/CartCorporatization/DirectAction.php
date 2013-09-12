<?php
/**
 * Корзина акционирования. Прямая схема.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DirectAction extends CAction
{
    /**
     * @param string $type
     * @param string $oid
     * @throws CHttpException
     */
    public function run($type = MTypeOrganization::ORGANIZATION, $oid='')
    {
        /**
         * @var Cart_corporatizationController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Прямая схема';

        $organization_id = '';
        $individual_id = '';
        $individuals = array();
        $data = array();

        if ($type === MTypeOrganization::ORGANIZATION){
            $currentTab = 'organization';
            $organizations = Organization::model()->getListNames($controller->getForceCached());
            if (!empty($oid)){
                $org = Organization::model()->findByPk($oid, $controller->getForceCached());

                $organization_id = $org->primaryKey;
                $data = DirectShareholding::model()->listModels($org->primaryKey, $org->type, $controller->getForceCached());
                $individuals = DirectShareholding::model()->getIndividuals($org->primaryKey, $org->type, $controller->getForceCached());
            }
        } elseif ($type === MTypeOrganization::CONTRACTOR){
            $currentTab = 'contractor';
            $organizations = Contractor::model()->getListNames($controller->getForceCached());
            if (!empty($oid)){
                $org = Contractor::model()->findByPk($oid, $controller->getForceCached());

                $organization_id = $org->primaryKey;
                $data = DirectShareholding::model()->listModels($org->primaryKey, $org->type, $controller->getForceCached());
                $individuals = DirectShareholding::model()->getIndividuals($org->primaryKey, $org->type, $controller->getForceCached());
            }
        } else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $controller->render('/cart_corporatization/index',
            array(
                'content' => $controller->renderPartial(
                    '/cart_corporatization/cart_direct',
                    array(
                        'data' => $data,
                    ),
                    true
                ),
                'org_type' => $type,
                'organization_id' => $organization_id,
                'individual_id' => $individual_id,
                'organizations' => $organizations,
                'individuals' => $individuals,
                'cur_tab' => $currentTab,
                'scheme' => 'direct',
            )
        );
    }
}