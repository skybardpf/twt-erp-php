<?php
/**
 * Просмотр бенефициара.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр бенефициара
     * @param string $id                Идентификатор лица
     * @param string $type_lico         Тип лица
     * @param string $org_id            Идентификатор организации
     * @param string $org_type          Тип организации
     * @param string $date              Дата
     * @param string $number_stake      Номер пакета акций
     * @throws CHttpException
     */
    public function run($id, $type_lico, $org_id, $org_type, $date, $number_stake)
    {
        /**
         * @var Interested_person_beneficiaryController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр бенефициара';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        if ($org_type === MTypeOrganization::ORGANIZATION){
            $org = Organization::model()->findByPk($org_id, $forceCached);
            $render_page = '/organization/show';
            $controller->menu_current = 'legal';
        } elseif ($org_type === MTypeOrganization::CONTRACTOR){
            $org = Contractor::model()->findByPk($org_id, $forceCached);
            $render_page = '/contractor/menu_tabs';
            $controller->menu_current = 'contractors';
        } else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $model = InterestedPersonBeneficiary::model()->findByPk($id, $type_lico, $org_id, $org_type, $date, $number_stake, $forceCached);

        $controller->render($render_page, array(
            'content' => $controller->renderPartial('/interested_person_beneficiary/view',
                array(
                    'model' => $model,
                    'organization' => $org,
                ), true
            ),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}