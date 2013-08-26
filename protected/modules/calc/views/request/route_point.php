<?php
/**
 * User: Forgon
 * Date: 12.04.13
 */
/* @var $this RequestController */
/* @var $iteration string */
/* @var $name string */
/* @var $point array */

$countries = Country::model()->getDataNames();
?>
<div class="row-fluid">
	<div class="span3"><label class="pull-right">Страна</label></div>
	<div class="span6">
		<?=CHtml::dropDownList(
			'order[route]['.$name.']'.($iteration ? '['.$iteration.']' : '').'[Country]',
			(isset($point['Country']) && $point['Country']) ? $point['Country'] : '',
			array('' => 'Не выбрана') + $countries,
			array('data-route_input' => 1, 'data-country_input' => '1')+($iteration == '__iteration__' ? array('disabled' => 'disabled') : array()))?>
	</div>
</div>
<div class="row-fluid">
	<div class="span3"><label class="pull-right">Город</label></div>
	<div class="span6">
		<?=CHtml::dropDownList(
			'order[route]['.$name.']'.($iteration ? '['.$iteration.']' : '').'[City]',
			(isset($point['City']) && $point['City']) ? $point['City'] : '',
			array('' => 'Не выбран')+((isset($point['Country']) && $point['Country']) ? $this->getCitiesList($point['Country']) : array()),
			array('data-route_input' => 1, 'data-city_input' => '1')+($iteration == '__iteration__' ? array('disabled' => 'disabled') : array()))?>
	</div>
</div>
<div class="row-fluid">
	<div class="span3"><label class="pull-right">Транспорт</label></div>
	<div class="span6">
        <?=CHtml::dropDownList(
            'order[route]['.$name.']'.($iteration ? '['.$iteration.']' : '').'[Transport]',
            (isset($point['Transport']) && $point['Transport']) ? $point['Transport'] : '',
            array(
                '' => 'Не выбран',
                '30' => 'Автодорожный транспорт, за исключением транспортных средств, указанных под кодами 31, 32',
                '80' => 'Внутренний водный транспорт',
                '40' => 'Воздушный транспорт',
                '20' => 'Железнодорожный транспорт',
                '10' => 'Морской/речной транспорт',
                '50' => 'Почтовое отправление',
            ),
            array('data-route_input' => 1)+($iteration == '__iteration__' ? array('disabled' => 'disabled') : array()))?>
	</div>
</div>
<div class="row-fluid">
	<div class="span3"><label>Номер транспортного средства</label></div>
	<div class="span6">
		<?=CHtml::textField('order[route]['.$name.']'.($iteration ? '['.$iteration.']' : '').'[RegistrationNumber]',
			isset($point['RegistrationNumber']) ? $point['RegistrationNumber'] : '',
			array('data-route_input' => 1)+($iteration == '__iteration__' ? array('disabled' => 'disabled') : array()))?>
	</div>
</div>