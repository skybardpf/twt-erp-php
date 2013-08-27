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
            '_html_form_select_element' => 'application.controllers.My_events.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.controllers.My_events.HtmlRowElementAction',

            'index' => 'application.controllers.My_events.IndexAction',
            'add' => 'application.controllers.My_events.CreateAction',
            'view' => 'application.controllers.My_events.ViewAction',
            'edit' => 'application.controllers.My_events.UpdateAction',
            'delete' => 'application.controllers.My_events.DeleteAction',
        );
    }
}