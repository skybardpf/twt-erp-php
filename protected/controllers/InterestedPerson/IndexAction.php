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
                $model = new InterestedPersonLeader();
            } break;
            case MPageTypeInterestedPerson::MANAGER: {
                $controller->pageTitle .= ' | Список менеджеров';
                $model = new InterestedPersonManager();
            } break;
            case MPageTypeInterestedPerson::SECRETARY: {
                $controller->pageTitle .= ' | Список секретарей';
                $model = new InterestedPersonSecretary();
            } break;
            // По-умолчанию "Номинальный акционер"
            case MPageTypeInterestedPerson::SHAREHOLDER: {}
            case null: {
                $controller->pageTitle .= ' | Список номинальный акционеров';
                $type = 'shareholder';
                $model = new InterestedPersonShareholder();
            } break;
            default: {
                throw new CHttpException(404, 'Неизвестный тип заинтересованного лица');
            }
        }
        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($org_id, $forceCached);

        $history = $model->listHistory($org->primaryKey, MTypeOrganization::ORGANIZATION, $forceCached);
        $last_date = $model->getLastDate($org->primaryKey, MTypeOrganization::ORGANIZATION, $forceCached);
        $data = $model->listModels($org_id, MTypeOrganization::ORGANIZATION, $last_date, $forceCached);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'organization' => $org,
                    'menu_tab' => $type,
                    'content' => $controller->renderPartial('/interested_person_'.$type.'/list',
                        array(
                            'data' => $data,
                            'last_date' => $last_date,
                            'history' => $history,
                            'organization' => $org,
                            'type_person' => $type
                        ), true)
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}