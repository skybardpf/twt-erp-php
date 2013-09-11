<?php
/**
 * Корзина акционирования.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    /**
     * @throws CHttpException
     */
    public function run($type = MTypeOrganization::ORGANIZATION, $scheme = 'direct', $oid='', $iid='')
    {
        /**
         * @var Cart_corporatizationController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр';

        if (!in_array($scheme, array('direct', 'indirect')))
            throw new CHttpException(500, 'Указан неправильный тип схемы');

        $org_id = '';
        $individual_id = '';
        $individuals = array();
        if ($type === MTypeOrganization::ORGANIZATION){
            if (!empty($oid)){
                $org = Organization::model()->findByPk($oid, $controller->getForceCached());
                $org_id = $org->primaryKey;
            }
            $currentTab = 'organization';
            $organizations = Organization::model()->getListNames($controller->getForceCached());
        } elseif ($type === MTypeOrganization::CONTRACTOR){
            if (!empty($oid)){
                $org = Contractor::model()->findByPk($oid, $controller->getForceCached());
                $org_id = $org->primaryKey;
            }
            $currentTab = 'contractor';
            $organizations = Contractor::model()->getListNames($controller->getForceCached());
        } else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $controller->render('/cart_corporatization/index',
            array(
                'content' => $controller->renderPartial(
                    '/cart_corporatization/cart_'.$scheme,
                    array(
                        'data' => array(),
                    ),
                    true
                ),
                'org_type' => $type,
                'organization_id' => $org_id,
                'individual_id' => $individual_id,
                'organizations' => $organizations,
                'individuals' => $individuals,
                'cur_tab' => $currentTab,
                'scheme' => $scheme,
            )
        );
    }
}