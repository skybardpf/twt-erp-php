<?php
/* @var $this EntitiesController */
/* @var $element LegalEntities */

$this->breadcrumbs=array(
	'Юридические лица' => array($this->createUrl('/legal/entities/')),
	'Просмотр',
);
?>
<h2><?=$element->full_name?></h2>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $element,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'full_name',    'label' => 'Полное имя'),
			array('name' => 'country',      'label' => 'Страна юрисдикции'),
			array('name' => 'resident',     'label' => 'Не является резидентом РФ', 'type' => 'raw', 'value' => $element->resident ? 'Да' : 'Нет'),
			array('name' => 'type_no_res',  'label' => 'Тип нерезидента'),
			array('name' => 'contragent',   'label' => 'Контрагент', 'type' => 'raw', 'value' => $element->contragent ? 'Сторонее лицо' : 'Cобственное лицо'),
			array('name' => 'group_name',   'label' => 'Группа контрагентов'),
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
