<?php
/**
 * User: Forgon
 * Date: 08.04.13
 */
/**
 * @var $this RequestController
 * @var $insurance array
 */

Yii::app()->clientScript->registerScriptFile($this->module->baseAssets.'/js/postmessage.js');
Yii::app()->clientScript->registerScriptFile($this->module->baseAssets.'/js/Frame.js');
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

	echo CHtml::hiddenField('order_number', isset($insurance['NumberOfPreOrder']) ? $insurance['NumberOfPreOrder'] : 0);
	echo CHtml::hiddenField('order_date', isset($insurance['DateOfPreOrder']) ? $insurance['DateOfPreOrder'] : '');

    foreach ($insurance['variants'] as $k=>$var) {
		echo CHtml::hiddenField('variants['.$var['number'].'][company]', $var["company"]);
		echo CHtml::hiddenField('variants['.$var['number'].'][company_title]', $var["company_title"]);
		echo CHtml::hiddenField('variants['.$var['number'].'][ins_type]', $var["ins_type"]);
		echo CHtml::hiddenField('variants['.$var['number'].'][cost]', $var["cost"]);
		echo CHtml::hiddenField('variants['.$var['number'].'][franchise]', $var["franchise"]);
		echo CHtml::hiddenField('variants['.$var['number'].'][guard]', $var["guard"]);
		echo CHtml::hiddenField('variants['.$var['number'].'][currency]', $var["currency"]);
		echo CHtml::hiddenField('variants['.$var['number'].'][number]', $var['number']);
	}

	$gridDataProvider = new CArrayDataProvider($insurance['variants'], array('keyField' => false));
    $currency = Currencies::getValues();
    foreach($gridDataProvider->rawData as $k=>$v){
        $gridDataProvider->rawData[$k]['franchise'] = $v["franchise"].((isset($v["currency"]) && isset($currency[$v["currency"]])) ? " ".$currency[$v["currency"]] : "");
        $gridDataProvider->rawData[$k]['cost'] = $v["cost"].((isset($v["currency"]) && isset($currency[$v["currency"]])) ? " ".$currency[$v["currency"]] : "");
    }
	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'          => 'striped bordered condensed',
		'dataProvider'  => $gridDataProvider,
		'template'      => '{items}',
		'columns'       => array(
			array(
                'name' => 'variant',
                'header' => '',
                'type' => 'raw',
                'value' => 'CHtml::radioButton("variant", isset($data["selected"]), array("value" => $data["number"]))'
            ),
			array('name' => 'company_title', 'header' => 'Компания'),
			array('name' => 'ins_type',      'header' => 'Вид страхования'),
			array('name' => 'cost',
                'header' => 'Тариф',
                'type' => 'raw',
                'htmlOptions' => array('width' => '13%'),
            ),
			array(
                'name' => 'franchise',
                'header' => 'Франшиза',
                'type' => 'raw',
            ),
			array(
                'name' => 'guard',
                'header' => 'Требуется ли охрана?',
                'type' => 'raw',
                'value' => '$data["guard"] == "true" ? "Да" : "Нет"'
            ),
		),
	));
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type' => 'primary',
		'label'=> 'Заказать')
	);

$this->endWidget();
?>

