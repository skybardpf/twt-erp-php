<?php
/**
 * Управление моими событиями (мероприятиями).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class My_eventsController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'my_events';
    public $defaultAction = 'index';
    public $pageTitle = 'TWT Consult | Мои события';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            '_html_form_select_element' => 'application.modules.legal.controllers.My_events.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.modules.legal.controllers.My_events.HtmlRowElementAction',

            'index' => 'application.modules.legal.controllers.My_events.IndexAction',
            'add' => 'application.modules.legal.controllers.My_events.CreateAction',
            'view' => 'application.modules.legal.controllers.My_events.ViewAction',
            'edit' => 'application.modules.legal.controllers.My_events.UpdateAction',
            'delete' => 'application.modules.legal.controllers.My_events.DeleteAction',

            'delete_file' => 'application.modules.legal.controllers.My_events.Delete_fileAction',
            'download_archive' => 'application.modules.legal.controllers.My_events.Download_archiveAction',
            'download_file' => 'application.modules.legal.controllers.My_events.Download_fileAction',
        );
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

    /**
     * Получаем список событий.
     * @return Event[]
     */
    public function getDataProvider()
    {
        $cache_id = get_class(Event::model()).'_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = Event::model()
                ->where('deleted', false)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Создаем новое событие.
     * @return Event
     */
    public function createModel()
    {
        $model = new Event();
        return $model;
    }
}