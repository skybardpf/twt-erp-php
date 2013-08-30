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
     */
    public function run($org_id, $type=null)
    {
        /**
         * @var Interested_personController $controller
         */
        $controller = $this->controller;

        switch ($type){
            case 'leader': {
                $controller->pageTitle .= ' | Список руководителей';
                $page = 'list_leader';
            } break;
            case 'manager': {
                $controller->pageTitle .= ' | Список менеджеров';
                $page = 'list_manager';
            } break;
            case 'secretary': {
                $controller->pageTitle .= ' | Список секретарей';
                $page = 'list_secretary';
            } break;
            // По-умолчанию "Номинальный акционер"
            case 'shareholder': {}
            default: {
                $controller->pageTitle .= ' | Список номинальный акционеров';
                $type = 'shareholder';
                $page = 'list_shareholder';
            }
        }
        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($org_id, $forceCached);

//        $data = $controller->getIndexProviderModel($org_id);
        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'organization' => $org,
                    'menu_tab' => $type,
                    'content' => $controller->renderPartial('/interested_person/'.$page,
                        array(
                            'data' => array(),
                            'organization' => $org,
                        ), true)
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}