<?php
/**
 *  Управление учредительными документами.
 *
 *  @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Founding_documentController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $pageTitle = 'TWT Consult | Документы | Учредительные документы';

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'view' => 'application.controllers.FoundingDocument.ViewAction',
            'edit' => 'application.controllers.FoundingDocument.UpdateAction',
            'add' => 'application.controllers.FoundingDocument.CreateAction',
            'delete' => 'application.controllers.FoundingDocument.DeleteAction',
        );
    }
}