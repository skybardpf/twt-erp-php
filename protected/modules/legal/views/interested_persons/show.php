<?php
/* @var $this EntitiesController */
/* @var $element LegalEntities */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Просмотр',
);

LegalEntities::getValues();
Currencies::getValues();
?>
<h2><?=$element->role?></h2>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $element,
		'attributes'=>array(
			//array('name' => 'id',           'label' => 'Лицо', type),
			array('name' => 'id_yur',         'label' => 'Юр.Лицо', 'type' => 'raw', 'value' => ($element->id_yur && isset(LegalEntities::$values[$element->id_yur])) ? LegalEntities::$values[$element->id_yur] : "Не указано"),
			array('name' => 'role',           'label' => 'Роль'),
			array('name' => 'cost',           'label' => 'Номинальная стоимость пакета акций'),
			array('name' => 'percent',        'label' => 'Величина пакета акций'),
			array('name' => 'vid',            'label' => 'Вид лица'),
			array('name' => 'cur',            'label' => 'Валюта номинальной стоимости', 'type' => 'raw', 'value' => ($element->cur && isset(Currencies::$values[$element->cur])) ? Currencies::$values[$element->cur] : "Не указана"),
			array('name' => 'add_info',       'label' => 'Дополнительные сведения'),
			/*array('name' => 'eng_name',     'label' => 'Английское наименование'),
			array('name' => 'country',      'label' => 'Страна юрисдикции', 'type' => 'raw', 'value' => $element->country ? Countries::$values[$element->country] : 'Не указана'),


			 <!--'id'            => 'Лицо',
	    'role'          => 'Роль',
	    'add_info'      => 'Дополнительные сведения',
	    'cost'          => 'Номинальная стоимость пакета акций',
	    'percent'       => 'Величина пакета акций',
	    'vid'           => 'Вид лица', // (выбор из справочника юр. лиц или физ. лиц, обязательное); Физические лица
	    'cur'           => 'Валюта номинальной стоимости',
	    'deleted'       => 'Удален',
	    'id_yur'        => 'Юр.Лицо'-->
			*/
		)
	));
	?>
</div>
