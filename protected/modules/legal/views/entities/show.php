<?php
/* @var $this EntitiesController */
/* @var $element LegalEntities */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Просмотр',
);

Countries::getValues();
CounterpartiesGroups::getValues();
?>
<h2><?=$element->full_name?></h2>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $element,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'full_name',    'label' => 'Полное наименование'),
			array('name' => 'eng_name',     'label' => 'Английское наименование'),
			array('name' => 'country',      'label' => 'Страна юрисдикции', 'type' => 'raw', 'value' => $element->country ? Countries::$values[$element->country] : 'Не указана'),
			array('name' => 'resident',     'label' => 'Не является резидентом РФ', 'type' => 'raw', 'value' => $element->resident ? 'Да' : 'Нет'),
			array('name' => 'type_no_res',  'label' => 'Тип нерезидента', 'type' => 'raw', 'value' => $element->type_no_res ? $element->NonResidentValues[$element->type_no_res] : 'Не указан'),
			array('name' => 'contragent',   'label' => 'Контрагент', 'type' => 'raw', 'value' => $element->contragent ? 'Сторонее лицо' : 'Cобственное лицо'),
			array('name' => 'parent',       'label' => 'Группа контрагентов', 'type' => 'raw', 'value' => $element->parent ? CounterpartiesGroups::$values[$element->parent] : 'Не указана'),
			array('name' => 'comment',      'label' => 'Комментарий'),
			array('name' => 'inn',          'label' => 'ИНН'),
			array('name' => 'inn',          'label' => 'КПП'),
			array('name' => 'ogrn',         'label' => 'ОГРН'),
			array('name' => 'yur_address',  'label' => 'Адрес юридический'),
			array('name' => 'fact_address', 'label' => 'Адрес фактический'),
			array('name' => 'reg_nom',      'label' => 'Регистрационный номер'),
			array('name' => 'sert_nom',     'label' => 'Номер сертификата о регистрации'),
			array('name' => 'sert_date',    'label' => 'Дата сертификата о регистрации'),
			array('name' => 'vat_nom',      'label' => 'VAT-номер'),
			array('name' => 'profile',      'label' => 'Основной вид деятельности'),
		)
	));
	?>
</div>
