<?php
/**
 * Редактирование "Бенефициара".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UpdateAction extends CAction
{
    /**
     * Редактирование "Бенефициара".
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
        $controller->pageTitle .= ' | Редактирование бенефициара';

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

        /**
         * @var InterestedPersonBeneficiary $model
         */
        $model = InterestedPersonBeneficiary::model()->findByPk($id, $type_lico, $org_id, $org_type, $date, $number_stake, $forceCached);
        $model->individual_id = $model->organization_id = $model->contractor_id = $model->primaryKey;

        if(isset($_POST['ajax']) && $_POST['ajax'] === 'form-person') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        /**
         * Сохраняем для правильной очистки кеша.
         */
        $old_model = clone $model;

        $data = Yii::app()->request->getPost(get_class($model));
        if ($data) {
            $model->setAttributes($data);
            if ($model->validate()) {
                try {
                    $ret = $model->save($old_model);
                    if ($ret === null)
                        throw new CException('Ошибка при сохранении бенефициара');

                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $ret['id'],
                            'type_lico' => $ret['type_lico'],
                            'org_id' => $ret['id_yur'],
                            'org_type' => $ret['type_yur'],
                            'date' => $ret['date'],
                            'number_stake' => $ret['number_stake'],
                        )
                    ));
                } catch (CException $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $controller->render($render_page, array(
            'content' => $controller->renderPartial('/interested_person_beneficiary/form',
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