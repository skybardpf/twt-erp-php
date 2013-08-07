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
        /**
         * @var ContractorController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список контрагентов';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $data = Contractor::model()->getDataGroupBy($force_cache);
        $groups = ContractorGroup::model()->getTreeContractors($data, $force_cache);

        $controller->render(
            'index',
            array(
                'data' => $groups
            )
        );
    }
}