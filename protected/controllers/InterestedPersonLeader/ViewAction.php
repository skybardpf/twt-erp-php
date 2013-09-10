<?php
/**
 * Просмотр Руководителя
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр Руководителя
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
         * @var Interested_person_leaderController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр руководителя';

        if ($org_type === MTypeOrganization::ORGANIZATION)
            $org = Organization::model()->findByPk($org_id, $controller->getForceCached());
        elseif ($org_type === MTypeOrganization::CONTRACTOR)
            $org = Contractor::model()->findByPk($org_id, $controller->getForceCached());
        else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $model = InterestedPersonLeader::model()->findByPk($id, $type_lico, $org_id, $org_type, $date, $number_stake, $controller->getForceCached());

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'organization' => $org,
                    'menu_tab' => $model->pageTypePerson,
                    'content' => $controller->renderPartial('/interested_person_leader/view',
                        array(
                            'model' => $model,
                            'organization' => $org,
                        ), true)
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}