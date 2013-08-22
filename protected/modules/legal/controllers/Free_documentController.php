<?php
/**
 * Управление свободными документами.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
            'view' => 'application.modules.legal.controllers.FreeDocument.ViewAction',
            'edit' => 'application.modules.legal.controllers.FreeDocument.UpdateAction',
            'add' => 'application.modules.legal.controllers.FreeDocument.CreateAction',
            'delete' => 'application.modules.legal.controllers.FreeDocument.DeleteAction',
        );
    }
}