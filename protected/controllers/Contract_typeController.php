<?php
/**
 * Управление Видами контрактов.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Contract_typeController extends Controller
{
    public $layout = 'inner';
    public $menu_current = 'contract_types';
    public $pageTitle = 'TWT Consult | Виды договоров';

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.controllers.ContractType.IndexAction',
            'edit' => 'application.controllers.ContractType.UpdateAction',
            'add' => 'application.controllers.ContractType.CreateAction',
        );
    }
}