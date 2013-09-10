<?php
/**
 * Удаление бенефициара.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление бенефициара.
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
        $controller->pageTitle .= ' | Удаление бенефициара';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        if ($org_type === MTypeOrganization::ORGANIZATION)
            $org = Organization::model()->findByPk($org_id, $forceCached);
        elseif ($org_type === MTypeOrganization::CONTRACTOR)
            $org = Contractor::model()->findByPk($org_id, $forceCached);
        else
            throw new CHttpException(500, 'Указан неизвестный тип организации');

        $model = InterestedPersonBeneficiary::model()->findByPk($id, $type_lico, $org_id, $org_type, $date, $number_stake, $forceCached);

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                $model->delete();
            } catch (Exception $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        }
        if (isset($_POST['result'])) {
            switch ($_POST['result']) {
                case 'yes':
                    if ($model->delete()) {
                        $controller->redirect($controller->createUrl('interested_person_beneficiary/index', array(
                            'org_id' => $org->primaryKey,
                            'org_type' => $org->type,
                        )));
                    } else {
                        throw new CHttpException(500, 'Не удалось удалить бенефициара.');
                    }
                break;
                default:
                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $model->primaryKey,
                            'type_lico' => $model->type_lico,
                            'org_id' => $model->id_yur,
                            'org_type' => $model->type_yur,
                            'date' => $model->date,
                            'number_stake' => $model->number_stake,
                        )
                    ));
                break;
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/delete',
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