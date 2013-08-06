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

        $data = Contractor::model()->getDataGroupBy(/*true*/);
        $groups = ContractorGroup::model()->getTreeData($data/*, true*/);

        $controller->render(
            'index',
            array(
                'data' => $groups
            )
        );
    }
}