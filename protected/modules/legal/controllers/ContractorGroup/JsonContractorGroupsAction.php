<?php
/**
 * Only Ajax. Список контрагентов по группам.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class JsonContractorGroupsAction extends CAction
{
    /**
     * Only Ajax. Список контрагентов по группам.
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest){
            $children_groups = ContractorGroup::model()->getTreeOnlyGroup(true);
            $ret = array(
                array(
                    'text' => 'Все группы',
                    'children' => $children_groups,
                    'expanded' => true
                ),
            );
            echo CJSON::encode($ret);
        }
    }
}