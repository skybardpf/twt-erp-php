<?php
/**
 * Список контрагентов (внешних организаций).
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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
            'index_menu_tabs',
            array(
                'content' => $controller->renderPartial(
                    'tab_index',
                    array(
                        'data' => $groups
                    ),
                    true
                ),
                'current_tab_menu' => 'contractor'
            )
        );
    }
}
