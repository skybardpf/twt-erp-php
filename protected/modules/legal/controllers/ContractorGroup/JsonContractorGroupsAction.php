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
            $dropdown = (isset($_GET['type']) && $_GET['type'] == 'dropdown');

//            var_dump($dropdown);
//            var_dump($_GET['type']);die;

//            $data = Contractor::model()->getDataGroupBy();
            $children_groups = ContractorGroup::model()->getTreeOnlyGroup($dropdown);
            $label = ($dropdown) ? 'label' : 'text';
            $ret = array(
                array(
                    $label => 'Все группы',
                    'children' => $children_groups,
                    'expanded' => true
                ),
            );
            echo CJSON::encode($ret);
        }
    }
}