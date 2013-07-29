<?php
/**
 * Банковские счета -> Список.
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Settlement_accountsController  $this
 * @var SettlementAccount[]            $data
 * @var Organizations                  $organization
 */
?>

<div class="pull-right" style="margin-top: 15px;">
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новый банковский счёт',
        'type'  => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'  => 'normal', // null, 'large', 'small' or 'mini'
        'url'   => $this->createUrl("add", array('org_id' => $organization->primaryKey))
    ));
?>
</div>

<h3>Банковские счета</h3>
<?php
    $provider = new CArrayDataProvider($data);
    $cur = Currencies::getValues();
    $p   = Individuals::getValues();
    foreach ($provider->rawData as $k=>$v){
        $person = '';
        if (!empty($provider->rawData[$k]->managing_persons)){
            foreach ($provider->rawData[$k]->managing_persons as $pid){
                if (isset($p[$pid])){
                    $person .= CHtml::link($p[$pid], $this->createUrl('/legal/individuals/view/', array('id' => $pid)));
                } else {
                    $person .= $pid;
                }
                $person .= '<br/>';
            }
        }
        $provider->rawData[$k]['div_persons'] = $person;
        $provider->rawData[$k]['cur_name'] = (isset($cur[$v['cur']])) ? $cur[$v['cur']] : NULL;
    }

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'dataProvider' => $provider,
        'template'=>"{items}{pager}",
        'columns'=>array(
            array(
                'name'  => 's_nom',
                'header'=> 'Номер счета',
                'type'  => 'raw',
                'value' => 'CHtml::link($data["s_nom"], Yii::app()->getController()->createUrl("settlement_accounts/view", array("id" => $data["id"])))'
            ),
//            array(
//                'name'  => 'id',
//                'header'=> '#',
//                'type'  => 'raw',
//                'value' => 'CHtml::link($data["id"], Yii::app()->getController()->createUrl("settlement", array("action" => "show", "id" => $data["id"])))'
//            ),
//            array(
//                'name'  => 'id_yur',
//                'header'=> 'Тип юр. лица',
//                'value' => '$data["id_yur"]'
//            ),
            array(
                'name'  => 'cur',
                'header'=> 'Валюта',
                'value' => '(is_null($data["cur_name"])) ? "Не задано" : $data["cur_name"]'
            ),
            array(
                'name'  => 'bank_name',
                'header'=> 'Банк'
            ),
            array(
                'name'  => 'div_persons',
                'type'  => 'raw',
                'header'=> 'Управляющая персона',
            ),
        ),
    ));

//    $this->endContent();
?>