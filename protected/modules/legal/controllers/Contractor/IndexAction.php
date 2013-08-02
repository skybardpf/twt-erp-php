<?php
/**
 * Список контрагентов (внешних организаций).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class IndexAction extends CAction
{
    /**
     * Список контрагентов (внешних организаций).
     */
    public function run()
    {
//        var_dump('aaa');die;
        /**
         * @var ContractorController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список контрагентов';

//        $groups = ContractorGroup::model()->getData();
//        $data = Contractor::model()->getDataGroupBy();

        $controller->render(
            'index',
            array(
//                'data' => $data,
//                'groups' => $groups,
            )
        );
    }
}