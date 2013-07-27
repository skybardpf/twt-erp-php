<?php
/**
 *  Банковские счета -> Список.
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this      Settlement_accountsController
 *  @var $accounts  SettlementAccount[]
 */
?>

<?php
$this->breadcrumbs = array(
    'Банковские счета'
);
echo CHtml::tag('h2', array(), 'Банковские счета');

if (!$accounts) {
    echo 'Ни одного банковского счета не зарегистрировано.';
} else {
    $data = new CArrayDataProvider($accounts);

    $cur = Currencies::getValues();
    $p   = Individuals::getValues();
    $org = Organizations::getValues();
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
        $data->rawData[$k]['yur_name'] = (isset($org[$v['id_yur']])) ? $org[$v['id_yur']] : '';
    }

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'dataProvider' => $data,
//        'template'=>"{items}",
        'columns'=>array(
            array(
                'name'  => 's_nom',
                'header'=> 'Номер',
                'type'  => 'raw',
                'value' => 'CHtml::link($data["s_nom"], Yii::app()->getController()->createUrl("/legal/settlement_accounts/view", array("id" => $data["id"])))'
            ),
            array(
                'name'  => 'yur_name',
                'header'=> 'Организация',
//                'value' => '$data["type_yur"]'
            ),
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
}
?>