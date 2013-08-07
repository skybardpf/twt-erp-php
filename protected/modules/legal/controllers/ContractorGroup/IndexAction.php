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

        $data = ContractorGroup::model()->getTreeOnlyGroup(true);
        $controller->render(
            'index',
            array(
                'data' => $data,
            )
        );
    }
}