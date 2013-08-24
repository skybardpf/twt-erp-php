<?php
/**
 * Управление моими событиями (мероприятиями).
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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
            '_html_form_select_element' => 'application.modules.legal.controllers.MyEvent.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.modules.legal.controllers.MyEvent.HtmlRowElementAction',

            'index' => 'application.modules.legal.controllers.MyEvent.IndexAction',
            'add' => 'application.modules.legal.controllers.MyEvent.CreateAction',
            'view' => 'application.modules.legal.controllers.MyEvent.ViewAction',
            'edit' => 'application.modules.legal.controllers.MyEvent.UpdateAction',
            'delete' => 'application.modules.legal.controllers.MyEvent.DeleteAction',

            'delete_file' => 'application.modules.legal.controllers.MyEvent.Delete_fileAction',
            'download_archive' => 'application.modules.legal.controllers.MyEvent.Download_archiveAction',
            'download_file' => 'application.modules.legal.controllers.MyEvent.Download_fileAction',
        );
    }
}