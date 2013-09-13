<?php
/**
 * Управление свободными документами.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Free_documentController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $pageTitle = 'TWT Consult | Организации | Свободные документы';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'view' => 'application.controllers.FreeDocument.ViewAction',
            'edit' => 'application.controllers.FreeDocument.UpdateAction',
            'add' => 'application.controllers.FreeDocument.CreateAction',
            'delete' => 'application.controllers.FreeDocument.DeleteAction',
        );
    }
}