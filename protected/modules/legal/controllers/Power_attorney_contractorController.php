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
            'list' => 'application.modules.legal.controllers.Power_attorney_contractor.ListAction',
            'view' => 'application.modules.legal.controllers.Power_attorney_contractor.ViewAction',
            'edit' => 'application.modules.legal.controllers.Power_attorney_contractor.UpdateAction',
            'add' => 'application.modules.legal.controllers.Power_attorney_contractor.CreateAction',
            'delete' => 'application.modules.legal.controllers.Power_attorney_contractor.DeleteAction',
        );
    }
}
