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

        $org_id = '';
        $individual_id = '';
        $individuals = array();
        $data = array();

        if ($scheme == 'indirect'){
            if (empty($oid))
                throw new CHttpException(500, 'Для косвенной схемы не указана организация');
            if (empty($iid))
                throw new CHttpException(500, 'Для косвенной схемы не указано физическое лицо');
            $individual = Individual::model()->findByPk($iid);
            $individual_id = $individual->primaryKey;
        } elseif ($scheme == 'direct'){

        } else
            throw new CHttpException(500, 'Указан неправильный тип схемы');

        if ($type === MTypeOrganization::ORGANIZATION){
            if (!empty($oid)){
                $org = Organization::model()->findByPk($oid, $controller->getForceCached());
                $org_id = $org->primaryKey;

                $data = DirectShareholding::model()->listModels($org->primaryKey, $org->type, $controller->getForceCached());
            }
            $currentTab = 'organization';
            $organizations = Organization::model()->getListNames($controller->getForceCached());
        } elseif ($type === MTypeOrganization::CONTRACTOR){
            if (!empty($oid)){
                $org = Contractor::model()->findByPk($oid, $controller->getForceCached());
                $org_id = $org->primaryKey;

                $data = DirectShareholding::model()->listModels($org->primaryKey, $org->type, $controller->getForceCached());
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
                        'data' => $data,
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