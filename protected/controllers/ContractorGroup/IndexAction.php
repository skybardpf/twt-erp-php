<?php
/**
 * Список групп контрагентов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
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
//        var_dump($data);die;

        $controller->render(
            '/contractor/index_menu_tabs',
            array(
                'content' => $controller->renderPartial(
                    'tab_index',
                    array(
                        'data' => $data
                    ),
                    true
                ),
                'current_tab_menu' => 'contractor_group'
            )
        );
    }
}
