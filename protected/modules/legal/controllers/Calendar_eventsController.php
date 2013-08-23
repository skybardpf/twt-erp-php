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
}