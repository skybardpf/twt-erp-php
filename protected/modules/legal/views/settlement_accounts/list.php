<?php
/**
 *  Банковские счета -> Список.
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this          Settlement_accountsController
 *  @var $accounts      SettlementAccount[]
 *  @var $organization  Organizations
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
    $data = new CArrayDataProvider($accounts);
    $cur = Currencies::getValues();
    $p   = Individuals::getValues();
    foreach ($data->rawData as $k=>$v){
        $person = '';
        if (!empty($data->rawData[$k]->managing_persons)){
            foreach ($data->rawData[$k]->managing_persons as $pid){
                if (isset($p[$pid])){
                    $person .= CHtml::link($p[$pid], $this->createUrl('/legal/individuals/view/', array('id' => $pid)));
                } else {
                    $person .= $pid;
                }
                $person .= '<br/>';
            }
        }
        $data->rawData[$k]['div_persons'] = $person;
        $data->rawData[$k]['cur_name'] = (isset($cur[$v['cur']])) ? $cur[$v['cur']] : NULL;
    }

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'dataProvider' => $data,
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