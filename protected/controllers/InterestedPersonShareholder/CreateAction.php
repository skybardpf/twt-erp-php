<?php
/**
 * Создание "Номинального акционера".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CreateAction extends CAction
{
    /**
     * Создание "Номинального акционера".
     * @param string $org_id    Идентификатор организации
     * @param string $org_type  Тип организации
     * @throws CHttpException
     */
    public function run($org_id, $org_type)
    {
        /**
         * @var Interested_person_shareholderController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Создание номинального акционера';

        $org = Organization::model()->findByPk($org_id, $controller->getForceCached());

        $model = new InterestedPersonShareholder();
        $model->id_yur = $org->primaryKey;
        $model->type_yur = MTypeOrganization::ORGANIZATION;
        $model->forceCached = $controller->getForceCached();

        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $type_lico = isset($data['type_lico']) ? $data['type_lico'] : null;
            if ($type_lico == MTypeInterestedPerson::ORGANIZATION) {
                $model->setScenario('typeOrganization');
            } elseif ($type_lico == MTypeInterestedPerson::INDIVIDUAL) {
                $model->setScenario('typeIndividual');
            } elseif ($type_lico == MTypeInterestedPerson::CONTRACTOR) {
                $model->setScenario('typeContractor');
            }
            $model->setAttributes($data);

            if ($model->validate()) {
                try {
                    $model->save();
                    $controller->redirect($controller->createUrl(
                        'interested_person/index',
                        array(
                            'org_id' => $org->primaryKey,
                            'type' => $model->pageTypePerson
                        )
                    ));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'organization' => $org,
                    'menu_tab' => $model->pageTypePerson,
                    'content' => $controller->renderPartial('/interested_person_shareholder/form',
                        array(
                            'model' => $model,
                            'organization' => $org,
                        ), true
                    ),
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}