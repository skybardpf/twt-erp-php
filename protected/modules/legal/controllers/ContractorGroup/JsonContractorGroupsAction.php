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
            $ret = ContractorGroup::model()->getTreeOnlyGroup(/*true*/);
            echo CJSON::encode($ret);
        }
    }
}