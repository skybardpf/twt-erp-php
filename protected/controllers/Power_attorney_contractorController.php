<?php
/**
 * Управление довереностями для контрагента.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Power_attorney_contractorController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'contractors';
    public $pageTitle = 'TWT Consult | Контрагенты | Доверенности';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'list' => 'application.controllers.PowerAttorneyContractor.ListAction',
            'view' => 'application.controllers.PowerAttorneyContractor.ViewAction',
            'edit' => 'application.controllers.PowerAttorneyContractor.UpdateAction',
            'add' => 'application.controllers.PowerAttorneyContractor.CreateAction',
            'delete' => 'application.controllers.PowerAttorneyContractor.DeleteAction',
        );
    }
}
