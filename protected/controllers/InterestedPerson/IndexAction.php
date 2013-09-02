<?php
/**
 * Список заинтересованных лиц. Разбито на 4 вкладки:
 * - Номинальные акционеры
 * - Руководители
 * - Менеджеры
 * - Секретари
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    /**
     * @param string $org_id
     * @param string $type
     * @throws CHttpException
     */
    public function run($org_id, $type=null)
    {
        /**
         * @var Interested_personController $controller
         */
        $controller = $this->controller;

        switch ($type){
            case MPageTypeInterestedPerson::LEADER: {
                $controller->pageTitle .= ' | Список руководителей';
                $page = 'list_leader';
                $model_class = 'InterestedPersonLeader';
            } break;
            case MPageTypeInterestedPerson::MANAGER: {
                $controller->pageTitle .= ' | Список менеджеров';
                $page = 'list_manager';
                $model_class = 'InterestedPersonManager';
            } break;
            case MPageTypeInterestedPerson::SECRETARY: {
                $controller->pageTitle .= ' | Список секретарей';
                $page = 'list_secretary';
                $model_class = 'InterestedPersonSecretary';
            } break;
            // По-умолчанию "Номинальный акционер"
            case MPageTypeInterestedPerson::SHAREHOLDER: {}
            case null: {
                $controller->pageTitle .= ' | Список номинальный акционеров';
                $type = 'shareholder';
                $page = 'list_shareholder';
                $model_class = 'InterestedPersonShareholder';
            } break;
            default: {
                throw new CHttpException(404, 'Неизвестный тип заинтересованного лица');
            }
        }
        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($org_id, $forceCached);

        /**
         * @var InterestedPersonAbstract $model
         */
        $model = SOAPModel::model($model_class);
//        $history = $model->listRevisionHistory($org->primaryKey, MTypeOrganization::ORGANIZATION, $forceCached);
//        $last_date = $model->getLastDate($org->primaryKey, MTypeOrganization::ORGANIZATION, $forceCached);

        $history = array();

        $last_date = new DateTime();
        $last_date = $last_date->format('Y-m-d');

        $data = $model->listModels($org_id, MTypeOrganization::ORGANIZATION, $last_date, $forceCached);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'organization' => $org,
                    'menu_tab' => $type,
                    'content' => $controller->renderPartial('/interested_person/'.$page,
                        array(
                            'data' => $data,
                            'last_date' => $last_date,
                            'history' => $history,
                            'organization' => $org,
                        ), true)
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}