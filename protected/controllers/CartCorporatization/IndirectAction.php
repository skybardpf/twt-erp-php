<?php
/**
 * Корзина акционирования. Косвенная схема
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndirectAction extends CAction
{
    /**
     * @param string $type
     * @param string $oid
     * @param string $iid
     * @throws CHttpException
     */
    public function run($type = MTypeOrganization::ORGANIZATION, $oid, $iid)
    {
        /**
         * @var Cart_corporatizationController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Косвенная схема';

        $individual = Individual::model()->findByPk($iid);
        if ($type === MTypeOrganization::ORGANIZATION){
            $org = Organization::model()->findByPk($oid, $controller->getForceCached());
            $currentTab = 'organization';
        } elseif ($type === MTypeOrganization::CONTRACTOR){
            $org = Contractor::model()->findByPk($oid, $controller->getForceCached());
            $currentTab = 'contractor';
        } else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $individuals = DirectShareholding::model()->getIndividuals($org->primaryKey, $org->type, $controller->getForceCached());
        if (!isset($individuals[$individual->primaryKey]))
            throw new CHttpException(500, 'Указанное физ. лицо не найдено в списке');

        $data = IndirectShareholding::model()->listModels($individual->primaryKey, $org->primaryKey, $org->type, $controller->getForceCached());
        $organizations = $org->getListNames($controller->getForceCached());

        $controller->render('/cart_corporatization/index',
            array(
                'content' => $controller->renderPartial(
                    '/cart_corporatization/cart_indirect',
                    array(
                        'data' => $data,
                        'individual' => $individual,
                    ),
                    true
                ),
                'org_type' => $type,
                'organization_id' => $org->primaryKey,
                'individual_id' => $individual->primaryKey,
                'organizations' => $organizations,
                'individuals' => $individuals,
                'cur_tab' => $currentTab,
                'scheme' => 'indirect',
            )
        );
    }
}