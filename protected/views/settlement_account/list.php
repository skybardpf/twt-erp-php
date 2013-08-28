<?php
/**
 * Банковские счета -> Список для организации.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Settlement_accountController $this
 * @var SettlementAccount[] $data
 * @var Organization $organization
 * @var bool $forceCache
 */
?>

    <div class="pull-right" style="margin-top: 15px;">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Новый банковский счёт',
            'type' => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'size' => 'normal', // null, 'large', 'small' or 'mini'
            'url' => $this->createUrl("add", array('org_id' => $organization->primaryKey))
        ));
        ?>
    </div>

    <h3>Банковские счета</h3>
<?php
$provider = new CArrayDataProvider($data);
$cur = Currency::model()->listNames($forceCache);
$p = Individual::model()->listNames($forceCache);
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
}

$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $provider,
    'template' => "{items}{pager}",
    'columns' => array(
        array(
            'name' => 's_nom',
            'header' => 'Номер счета',
            'type' => 'raw',
            'value' => 'CHtml::link($data["s_nom"], Yii::app()->getController()->createUrl("settlement_account/view", array("id" => $data["id"])))'
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
    ),
));