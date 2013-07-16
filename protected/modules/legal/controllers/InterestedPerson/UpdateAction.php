<?php
/**
 * Редакитрование Заинтересованного лица
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 */
class UpdateAction extends CAction
{
    /**
     * Редакитрование Заинтересованного лица
     * @param string $id       Идентификатор физ.лица
     * @param string $id_yur   Идентификатор Юр.лица
     * @param string $role     Роль
     * @param string $numPack  Номер пакета акций
     *
     * @throws CHttpException
     */
    public function run($id, $id_yur, $role, $numPack = null)
    {
        /**
         * @var $controller Interested_personsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' - Редактирование заинтересованного лица';

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
        if ($role == InterestedPerson::ROLE_BENEFICIARY){
            $model = $controller->loadModelBeneficiary($id, $id_yur, $numPack);
        } else {
            $model = $controller->loadModel($id, $id_yur, $role);
            if (!in_array($model->type_lico, array_keys(InterestedPerson::getPersonTypes()))){
                throw new CHttpException(404, 'Неправильный тип лица.');
            }
        }

        if (isset($_POST[get_class($model)])) {
            $model->setAttributes($_POST[get_class($model)]);

//            var_dump($model->typeStock);die;
            if ($model->validate()) {
                try {
                    $model->save();
//                    $controller->redirect($controller->createUrl(
//                        'view',
//                        array(
//                            'id' => $model->primaryKey,
//                            'id_yur' => $model->id_yur,
//                            'role' => $model->role,
//                        )
//                    ));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/my_organizations/show', array(
            'content' => $controller->renderPartial(
                '/interested_persons/form',
                array(
                    'model' => $model,
                    'organization' => $org,
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}