<?php
/**
 * Просмотр Менеджера
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр Менеджера
     * @param string $id        Идентификатор лица
     * @param string $type_lico Тип лица
     * @param string $id_yur    Идентификатор организации
     * @param string $type_yur  Тип организации
     * @param string $date      Дата
     * @param string $number_stake Номер пакета акций
     * @throws CHttpException
     */
    public function run($id, $type_lico, $id_yur, $type_yur, $date, $number_stake='')
    {
        /**
         * @var Interested_person_managerController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр менеджера';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($id_yur, $forceCached);
        $model = InterestedPersonManager::model()->findByPk($id, $type_lico, $id_yur, $type_yur, $date, $number_stake, $forceCached);

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