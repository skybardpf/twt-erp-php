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
    public function run($type = MTypeOrganization::ORGANIZATION, $scheme = 'direct')
    {
        /**
         * @var Cart_corporatizationController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр';

        if (!in_array($scheme, array('direct', 'indirect')))
            throw new CHttpException(500, 'Указан неправильный тип схемы');

        if ($type === MTypeOrganization::ORGANIZATION){
//            $org = Organization::model()->findByPk($org_id, $controller->getForceCached());
//            $render_page = '/organization/show';
            $currentTab = 'organization';
        } elseif ($type === MTypeOrganization::CONTRACTOR){
//            $org = Contractor::model()->findByPk($org_id, $controller->getForceCached());
//            $render_page = '/contractor/menu_tabs';
            $currentTab = 'contractor';
        } else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

//        $model = InterestedPersonBeneficiary::model();
//        $history = $model->listHistory($org->primaryKey, $org_type, $controller->getForceCached());
//        $last_date = $model->getLastDate($org->primaryKey, $org_type, $controller->getForceCached());
//        $data = $model->listModels($org_id, $org_type, $last_date, $controller->getForceCached());

        $controller->render('/cart_corporatization/index',
            array(
                'content' => 'adojnv',
                'cur_tab' => $currentTab,
                'scheme' => $scheme,
            )
        );
    }
}