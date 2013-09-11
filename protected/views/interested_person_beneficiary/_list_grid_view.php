<?php
/**
 * Список заинтересованных лиц: Бенефициары.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_person_beneficiaryController $this
 * @var InterestedPersonBeneficiary[] $data
 */

$currency = Currency::model()->listNames($this->getForceCached());
$provider = new CArrayDataProvider($data);
foreach($provider->rawData as $k=>$v)
    $provider->rawData[$k]['nominal_stake'] = $v['nominal_stake'] . ' ' . (isset($currency[$v['currency']]) ? $currency[$v['currency']] : '');

$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => new CArrayDataProvider($data),
    'template' => "{items} {pager}",
    'columns' => array(
        array(
            'name' => 'person_name',
            'type' => 'raw',
            'header' => 'Лицо'
        ),
        array(
            'name' => 'number_stake',
            'header' => 'Номер пакета акций',
        ),
        array(
            'name' => 'nominal_stake',
            'header' => 'Номинал акции',
        ),
        array(
            'name' => 'count_stake',
            'header' => 'Кол-во акций',
        ),
        array(
            'name' => 'value_stake',
            'header' => '%, акций',
        ),
    )
));