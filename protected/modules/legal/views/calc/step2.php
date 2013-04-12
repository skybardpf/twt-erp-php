<?php
/**
 * User: Forgon
 * Date: 08.04.13
 */
/* @var $this CalcController */
/* @var $insurance array */

Currencies::getValues();
?>
<h2><?=$this->controller_title?>. Шаг 2</h2>

<?php
	$this->widget('bootstrap.widgets.TbAlert', array(
		'block'     => true, // display a larger alert block?
		'fade'      => true, // use transitions?
		'closeText' => '&times;', // close link text - if set to false, no close link is displayed
		'alerts'    => array(
			'error' => array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
		),
	));

	/* @var $form TbActiveForm */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'    => 'calc-step2-form',
		'action' => $this->createUrl('step2'),
		'type'  => 'inline',
		'enableAjaxValidation' => false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	));

	echo CHtml::hiddenField('order_number', isset($insurance["NumberOfPreOrder"]) ? $insurance["NumberOfPreOrder"] : 0);
	foreach ($insurance['variants'] as $var) {
		echo CHtml::hiddenField('variants['.$var["number"].'][company]', $var["company"]);
		echo CHtml::hiddenField('variants['.$var["number"].'][company_title]', $var["company_title"]);
		echo CHtml::hiddenField('variants['.$var["number"].'][ins_type]', $var["ins_type"]);
		echo CHtml::hiddenField('variants['.$var["number"].'][cost]', $var["cost"]);
		echo CHtml::hiddenField('variants['.$var["number"].'][franchise]', $var["franchise"]);
		echo CHtml::hiddenField('variants['.$var["number"].'][guard]', $var["guard"]);
		echo CHtml::hiddenField('variants['.$var["number"].'][currency]', $var["currency"]);
		echo CHtml::hiddenField('variants['.$var["number"].'][number]', $var["number"]);
	}

	$gridDataProvider = new CArrayDataProvider($insurance['variants'], array('keyField' => false));

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'          => 'striped bordered condensed',
		'dataProvider'  => $gridDataProvider,
		'template'      => '{items}',
		'columns'       => array(
			array('name' => '', 'header' => '', 'type' => 'raw', 'value' => 'CHtml::radioButton("variant", isset($data["selected"]), array("value" => $data["number"]))'),
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
	);

$this->endWidget();
?>

