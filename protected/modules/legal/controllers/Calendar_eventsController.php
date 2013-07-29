<?php
/**
 * Управление событиями (мероприятиями) для кокретной организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class Calendar_eventsController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'calendar_events';
    public $pageTitle = 'TWT Consult | Мои организации';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'list' => 'application.modules.legal.controllers.Calendar_events.ListAction',
            'view' => 'application.modules.legal.controllers.Calendar_events.ViewAction',
            'edit' => 'application.modules.legal.controllers.Calendar_events.UpdateAction',
            'delete' => 'application.modules.legal.controllers.Calendar_events.DeleteAction',

            /**
             * Редирект на My_eventsController
             */
            'delete_file' => 'application.modules.legal.controllers.My_events.Delete_fileAction',
            'download_archive' => 'application.modules.legal.controllers.My_events.Download_archiveAction',
            'download_file' => 'application.modules.legal.controllers.My_events.Download_fileAction',

        );
    }

    /**
     * Получаем список событий указанной организации.
     * @param Organization $org
     * @return Event[]
     */
    public function getDataProvider(Organization $org)
    {
        $cache_id = get_class(Event::model()).'_list_org_id_'.$org->primaryKey;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = Event::model()
                ->where('id_yur', $org->primaryKey)
                ->where('deleted', false)
                ->findAll();

            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param string $id Идентификатор события.
     * @return Event
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $cache_id = get_class(Event::model()).'_'.$id;
        $model = Yii::app()->cache->get($cache_id);
        if ($model === false){
            $model = Event::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найдено событие.');
            }
            Yii::app()->cache->set($cache_id, $model, 0);
        }
        return $model;
    }
}