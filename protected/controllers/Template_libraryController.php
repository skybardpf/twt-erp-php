<?php
/**
 * Библиотека шаблонов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see TemplateLibrary
 */
class Template_libraryController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'template_library';

    public $pageTitle = 'TWT Consult | Библиотека шаблонов';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.controllers.TemplateLibrary.IndexAction',
//            'add' => 'application.controllers.Settlement_account.CreateAction',
//            'view' => 'application.controllers.Settlement_account.ViewAction',
//            'edit' => 'application.controllers.Settlement_account.UpdateAction',
//            'delete' => 'application.controllers.Settlement_account.DeleteAction',
        );
    }
}
