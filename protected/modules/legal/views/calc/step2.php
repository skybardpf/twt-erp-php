<?php
/**
 * User: Forgon
 * Date: 08.04.13
 */
/* @var $this CalcController */
/* @var $insurance array*/

Currencies::getValues();
?>
<h2><?=$this->controller_title?>. Шаг 2</h2>

<?php

$gridDataProvider = new CArrayDataProvider($insurance['variants'], array('keyField' => false));
$this->widget('bootstrap.widgets.TbGridView', array(
	'type'          => 'striped bordered condensed',
	'dataProvider'  => $gridDataProvider,
	'template'      => '{items}',
	'columns'       => array(
		array('name' => '', 'header' => '', 'type' => 'raw', 'value' => 'CHtml::radioButton("variant", false, array())'),
		array('name' => 'company_title', 'header' => 'Компания'),
		array('name' => 'ins_type',      'header' => 'Вид страхования'),
		array('name' => 'cost',          'header' => 'Тариф', 'type' => 'raw', 'htmlOptions' => array('width' => '13%'),  'value' => '$data["cost"].((isset($data["currency"]) && isset(Currencies::$values[$data["currency"]])) ? " ".Currencies::$values[$data["currency"]] : "")'),
		array('name' => 'franchise',     'header' => 'Франшиза', 'type' => 'raw', 'value' => '$data["franchise"].((isset($data["currency"]) && isset(Currencies::$values[$data["currency"]])) ? " ".Currencies::$values[$data["currency"]] : "")'),
		array('name' => 'guard',         'header' => 'Требуется ли охрана?', 'type' => 'raw', 'value' => '$data["guard"] == "true" ? "Да" : "Нет"'),
	),
));
$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type' => 'primary',
		'label'=> 'Заказать')
);?>
