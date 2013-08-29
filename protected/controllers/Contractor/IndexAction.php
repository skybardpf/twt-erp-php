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
         * @var ContractorController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список контрагентов';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $data = Contractor::model()->getDataGroupBy($forceCached);
        $groups = ContractorGroup::model()->getTreeContractors($data, $forceCached);

        $controller->render(
            'index_menu_tabs',
            array(
                'content' => $controller->renderPartial(
                    'tab_index',
                    array(
                        'data' => $groups,
                        'forceCached' => $forceCached
                    ),
                    true
                ),
                'current_tab_menu' => 'contractor'
            )
        );
    }
}
