<?php
/**
 * Банковские счета -> Список.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Settlement_accountController    $this
 * @var SettlementAccount[]             $data
 * @var bool                            $forceCached
 */

$this->breadcrumbs = array(
    'Банковские счета'
);
echo CHtml::tag('h2', array(), 'Банковские счета');


$provider = new CArrayDataProvider($data);

$cur = Currency::model()->listNames($forceCached);
$p = Individual::model()->listNames($forceCached);
$org = Organization::model()->getListNames($forceCached);
foreach ($provider->rawData as $k => $v) {
    $person = '';
    if (!empty($provider->rawData[$k]->managing_persons)) {
        foreach ($provider->rawData[$k]->managing_persons as $pid) {
            if (isset($p[$pid])) {
                $person .= '&bull;&nbsp;'.CHtml::link($p[$pid], $this->createUrl('individual/view/', array('id' => $pid)));
            } else {
                $person .= $pid;
            }
            $person .= '<br/>';
        }
    }
    $provider->rawData[$k]['managing_persons'] = $person;
    $provider->rawData[$k]['currency'] = (isset($cur[$v['currency']])) ? $cur[$v['currency']] : '---';
    $provider->rawData[$k]['id_yur'] = (isset($org[$v['id_yur']]))
        ? CHtml::link($org[$v['id_yur']], $this->createUrl('organization/view/', array('id' => $v['id_yur'])))
        : '---';
}

$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $provider,
    'template' => "{items} {pager}",
    'columns' => array(
        array(
            'name' => 's_nom',
            'header' => 'Номер',
            'type' => 'raw',
            'value' => 'CHtml::link($data["s_nom"], Yii::app()->getController()->createUrl("settlement_account/view", array("id" => $data["id"])))'
        ),
        array(
            'name' => 'id_yur',
            'type' => 'raw',
            'header' => 'Организация',
        ),
        array(
            'name' => 'currency',
            'header' => 'Валюта',
        ),
        array(
            'name' => 'bank_name',
            'header' => 'Банк'
        ),
        array(
            'name' => 'managing_persons',
            'type' => 'raw',
            'header' => 'Управляющая персона',
        ),
    )
));