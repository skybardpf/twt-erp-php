<?php
/**
 * Просмотр Заинтересованного лица
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр Заинтересованного лица
     * @param $id       Идентификатор физ.лица
     * @param $id_yur   Идентификатор Юр.лица
     * @param $role     Роль
     *
     * @throws CHttpException
     */
    public function run($id, $id_yur, $role)
    {
        /**
         * @var $controller Interested_personsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' - Просмотр заинтересованного лица';

        if (!in_array($role, InterestedPerson::getRoles())){
            throw new CHttpException(404, 'Указана неправильная роль.');
        }

        /**
         * @var $org Organizations
         */
        $org = Organizations::model()->findByPk('000000001');
        if ($org === null) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        /**
         * @var $model InterestedPerson
         */
        $model = $controller->loadModel($id, $id_yur, $role);
        if ($role == InterestedPerson::ROLE_BENEFICIARY){
//            $model = $controller->loadModelBeneficiary($id, $id_yur);
            $model->role = $role;
//            $model->date = '';
        }

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial('/interested_persons/show',
                array(
                    'model' => $model,
                    'organization' => $org,
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}