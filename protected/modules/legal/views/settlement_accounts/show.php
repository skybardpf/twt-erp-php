<?php
/* @var $this EntitiesController */
/* @var $element LegalEntities */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Просмотр',
);
LegalEntities::getValues();
?>
<h2><?=$element->name?></h2>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $element,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'name',         'label' => 'Название'),
			array('name' => 'id_yur',       'label' => 'Юр.лицо', 'type' => 'raw', 'value' => ($element->id_yur && isset(LegalEntities::$values[$element->id_yur]) )? LegalEntities::$values[$element->id_yur] : 'Не указано'),
			array('name' => 'bank',         'label' => 'Банк'),
			array('name' => 'corrbank',     'label' => 'Банк-корреспондент'),
			array('name' => 'recomend',     'label' => 'Рекомендатель'),
			array('name' => 'data_open',    'label' => 'Дата открытия'),
			array('name' => 'data_closed',  'label' => 'Дата закрытия'),
			array('name' => 's_nom',        'label' => 'Номер счета'),
			array('name' => 'cur',          'label' => 'Валюта'),
			array('name' => 'vid',          'label' => 'Вид счета'),
			array('name' => 'iban',         'label' => 'IBAN'),
			array('name' => 'contact',      'label' => 'Контакты в отделении'),
			array('name' => 'corr_account', 'label' => 'Счет банка-корреспондента'),
			/*
			'service'       => '',

			'address'       => '',
			'e_nom'         => '',
			*/
		))
	);
	?>
</div>
