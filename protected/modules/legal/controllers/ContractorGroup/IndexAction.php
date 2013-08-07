<?php
/**
 * Список групп контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class IndexAction extends CAction
{
    /**
     * Список групп контрагентов.
     */
    public function run()
    {
        /**
         * @var Contractor_groupController    $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список групп';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $data = ContractorGroup::model()->getTreeOnlyGroup($force_cache);

        $controller->render(
            'index',
            array(
                'data' => $data,
            )
        );
    }
}
