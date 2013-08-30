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

            } break;
            case 'manager': {

            } break;
            case 'secretary': {

            } break;
            // По-умолчанию "Номинальный акционер"
            case 'shareholder': {}
            default: {
                $controller->pageTitle .= ' | Список номинальный акционеров';
                $type = 'shareholder';
            }
        }
        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($org_id, $forceCached);

//        $data = $controller->getIndexProviderModel($org_id);
        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'data' => array(),
                    'organization' => $org,
                    'menu_tab' => $type,
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->current_tab,
        ));
    }
}