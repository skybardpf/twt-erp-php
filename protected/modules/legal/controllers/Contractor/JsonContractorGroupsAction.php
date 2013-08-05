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
            $data = Contractor::model()->getDataGroupBy(true);
            $groups = ContractorGroup::model()->getTreeData($data, true);
            $ret = array(
                array(
                    'text' => 'Все контрагенты',
                    'children' => $groups,
                    'expanded' => true
                ),
            );
            echo CJSON::encode($ret);
        }
    }
}