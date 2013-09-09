<?php
/**
 * Удаление номинального акционера.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление номинального акционера.
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
         * @var Interested_person_shareholderController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Удаление номинального акционера';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $org = Organization::model()->findByPk($id_yur, $forceCached);
        $model = InterestedPersonShareholder::model()->findByPk($id, $type_lico, $id_yur, $type_yur, $date, $number_stake, $forceCached);

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
                        $controller->redirect($controller->createUrl('interested_person/index', array(
                            'org_id' => $org->primaryKey,
                            'type' => $model->pageTypePerson
                        )));
                    } else {
                        throw new CHttpException(500, 'Не удалось удалить номинального акционера.');
                    }
                break;
                default:
                    $controller->redirect($controller->createUrl(
                        'view',
                        array(
                            'id' => $model->primaryKey,
                            'type_lico' => $model->type_lico,
                            'id_yur' => $model->id_yur,
                            'type_yur' => $model->type_yur,
                            'date' => $model->date,
                            'number_stake' => $model->number_stake,
                        )
                    ));
                break;
            }
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/interested_person/index',
                array(
                    'organization' => $org,
                    'menu_tab' => $model->pageTypePerson,
                    'content' => $controller->renderPartial('/interested_person/delete',
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