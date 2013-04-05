<?php
/* @var $this BeneficiaryController */
/* @var $element Beneficiary */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Просмотр',
);
LegalEntities::getValues();
Currencies::getValues();
?>
<h2>Бенефициар <?=$element->id?></h2>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $element,
		'attributes'=>array(
			array('name' => 'id',       'label' => 'Лицо (непонятное)'),
			array('name' => 'role',     'label' => 'Роль'),
			array('name' => 'id_yur',   'label' => 'Юр.Лицо', 'type' => 'raw', 'value' => ($element->id_yur && isset(LegalEntities::$values[$element->id_yur])) ? LegalEntities::$values[$element->id_yur] : "Не указано"),
			array('name' => 'add_info', 'label' => 'Дополнительные сведения'),
			array('name' => 'cost',     'label' => 'Номинальная стоимость пакета акций'),
			array('name' => 'percent',  'label' => 'Величина пакета акций в процентах'),
			array('name' => 'vid',      'label' => 'Вид лица'),
			array('name' => 'cur',      'label' => 'Валюта номинальной стоимости', 'type' => 'raw', 'value' => ($element->cur && isset(Currencies::$values[$element->cur])) ? Currencies::$values[$element->cur] : "Не указана"),
			array('name' => 'control',  'label' => 'Непонятное поле control',),
		)
	));
	?>
</div>
