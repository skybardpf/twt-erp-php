<?php
/**
 * Список бенефициаров. С историей изменений.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    /**
     * @param string $org_id
     * @param string $org_type
     * @throws CHttpException
     */
    public function run($org_id, $org_type)
    {
        /**
         * @var Interested_person_beneficiaryController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список бенефициаров';

        if ($org_type === MTypeOrganization::ORGANIZATION){
            $org = Organization::model()->findByPk($org_id, $controller->getForceCached());
            $render_page = '/organization/show';
            $controller->menu_current = 'legal';
        } elseif ($org_type === MTypeOrganization::CONTRACTOR){
            $org = Contractor::model()->findByPk($org_id, $controller->getForceCached());
            $render_page = '/contractor/menu_tabs';
            $controller->menu_current = 'contractors';
        } else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $model = InterestedPersonBeneficiary::model();
        $history = $model->listHistory($org->primaryKey, $org_type, $controller->getForceCached());
        $last_date = $model->getLastDate($org->primaryKey, $org_type, $controller->getForceCached());
        $data = $model->listModels($org_id, $org_type, $last_date, $controller->getForceCached());

        $controller->render($render_page,
            array(
                'content' => $controller->renderPartial(
                    '/interested_person_beneficiary/index',
                    array(
                        'data' => $data,
                        'last_date' => $last_date,
                        'history' => $history,
                        'organization' => $org,
                        'type_person' => $model->pageTypePerson
                    ), true),
                'organization' => $org,
                'cur_tab' => $controller->current_tab,
            )
        );
    }
}