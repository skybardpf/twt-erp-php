<?php
/**
 * Просмотр Менеджера
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр Менеджера
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
         * @var Interested_person_managerController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр менеджера';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        if ($org_type === MTypeOrganization::ORGANIZATION)
            $org = Organization::model()->findByPk($org_id, $forceCached);
        elseif ($org_type === MTypeOrganization::CONTRACTOR)
            $org = Contractor::model()->findByPk($org_id, $forceCached);
        else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $model = InterestedPersonManager::model()->findByPk($id, $type_lico, $org_id, $org_type, $date, $number_stake, $forceCached);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'organization' => $org,
                    'menu_tab' => $model->pageTypePerson,
                    'content' => $controller->renderPartial('/interested_person_manager/view',
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