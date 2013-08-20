<?php
/**
 * Управление довереностями для контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
            'list' => 'application.modules.legal.controllers.PowerAttorneyContractor.ListAction',
            'view' => 'application.modules.legal.controllers.PowerAttorneyContractor.ViewAction',
            'edit' => 'application.modules.legal.controllers.PowerAttorneyContractor.UpdateAction',
            'add' => 'application.modules.legal.controllers.PowerAttorneyContractor.CreateAction',
            'delete' => 'application.modules.legal.controllers.PowerAttorneyContractor.DeleteAction',
        );
    }
}
