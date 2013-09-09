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

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        if ($org_type === MTypeOrganization::ORGANIZATION)
            $org = Organization::model()->findByPk($org_id, $forceCached);
        elseif ($org_type === MTypeOrganization::CONTRACTOR)
            $org = Contractor::model()->findByPk($org_id, $forceCached);
        else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $model = InterestedPersonBeneficiary::model();
        $history = $model->listHistory($org->primaryKey, $org_type, $forceCached);
        $last_date = $model->getLastDate($org->primaryKey, $org_type, $forceCached);
        $data = $model->listModels($org_id, $org_type, $last_date, $forceCached);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person_beneficiary/index',
                array(
                    'data' => $data,
                    'last_date' => $last_date,
                    'history' => $history,
                    'organization' => $org,
                    'type_person' => $model->pageTypePerson
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}