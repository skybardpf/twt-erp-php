<?php
/**
 *  Документы юридического лица.
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class DocumentsController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'legal';

    public $pageTitle = 'TWT Consult | Организации | Документы';
    public $defaultAction = 'list';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'list' => 'application.modules.legal.controllers.Documents.ListAction',
        );
    }
}