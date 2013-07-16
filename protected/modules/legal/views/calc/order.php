<?php
/**
 * User: Forgon
 * Date: 11.04.13
 */

/* @var $this CalcController */
/* @var $order array */

Yii::app()->clientScript->registerScript('order_links', 'window.order_cities_link = "'.$this->createUrl('cities').'"');
Yii::app()->clientScript->registerScriptFile($this->module->assets.'/js/calc/order.js');

Countries::getValues();
?>
<h2 xmlns="http://www.w3.org/1999/html"><?=$this->controller_title?>. Шаг 3</h2>
<?php
$this->widget('bootstrap.widgets.TbAlert', array(
	'block'     => true, // display a larger alert block?
	'fade'      => true, // use transitions?
	'closeText' => '&times;', // close link text - if set to false, no close link is displayed
	'alerts'    => array(
		'error' => array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
	),
));
?>

<?php
	/* @var $form TbActiveForm */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'action' => $this->createUrl('order'),
		'id'    => 'calc-order-form',
		'type'  => 'inline',
		'enableAjaxValidation' => false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	));
	echo CHtml::hiddenField('order[NumberOfPreOrder]', isset($order["NumberOfPreOrder"]) ? $order["NumberOfPreOrder"] : 0);
	echo CHtml::hiddenField('order[DateOfPreOrder]', isset($order["DateOfPreOrder"]) ? $order["DateOfPreOrder"] : '');
?>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_CompanyName">Наименование компании</label></div>
		<div class="span9"><?=CHtml::textField('order[CompanyName]', isset($order['CompanyName']) ? $order['CompanyName'] : '', array('class' => 'span12'))?></div>
	</div><br/>
	<?php /*<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_inn">ИНН (не принимается)</label></div>
		<div class="span9"><?=CHtml::textField('order[inn]', isset($order['inn']) ? $order['inn'] : '', array('class' => 'span12'))?></div>
	</div><br/>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_kpp">КПП (не принимается)</label></div>
		<div class="span9"><?=CHtml::textField('order[kpp]', isset($order['kpp']) ? $order['kpp'] : '', array('class' => 'span12'))?></div>
	</div><br/>*/?>
	<?php /*if (Yii::app()->user->getState('ins_type', false) == 'Агентский') :*/?>
		<div class="row-fluid">
			<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_CompanyName">Выгодоприобретатель</label></div>
			<div class="span9"><?=CHtml::textField('order[Beneficiary]', isset($order['Beneficiary']) ? $order['Beneficiary'] : '', array('class' => 'span12'))?></div>
		</div><br/>
	<?php /*endif; */?>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_Consignment">Груз (товары через запятую)</label></div>
		<div class="span9"><?=CHtml::textArea('order[Consignment]', isset($order['Consignment']) ? $order['Consignment'] : '', array('class' => 'span12'))?></div>
	</div><br/>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_NumberOfSeat">Количество мест</label></div>
		<div class="span9"><?=CHtml::textField('order[NumberOfSeat]', isset($order['NumberOfSeat']) ? $order['NumberOfSeat'] : '', array('class' => 'span12'))?></div>
	</div><br/>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_NumberOfSeatMeasure">Единица измерения мест</label></div>
		<div class="span9"><?=CHtml::dropDownList('order[NumberOfSeatMeasure]', isset($order['NumberOfSeatMeasure']) ? $order['NumberOfSeatMeasure'] : '', array('' => 'Не выбрано') + $this->getSeatMeasures(), array('class' => 'span12'))?></div>
	</div><br/>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_Weight">Общий вес</label></div>
		<div class="span9"><?=CHtml::textField('order[Weight]', isset($order['Weight']) ? $order['Weight'] : '', array('class' => 'span12'))?></div>
	</div><br/>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_Documents">Список документов</label></div>
		<div class="span9"><?=CHtml::textArea('order[Documents]', isset($order['Documents']) ? $order['Documents'] : '', array('class' => 'span12'))?></div>
	</div><br/>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_StartDate">Начало страхования</label></div>
		<div class="span9">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'name' => 'order[StartDate]',
					'value' => isset($order['StartDate']) ? $order['StartDate'] : '',
					'options' => array(
						'showAnim' => '',
						//'minDate'  => '+0D',
						'dateFormat' => "yy-mm-dd"
					),
			));?>
		</div>
	</div><br/>
	<div class="row-fluid">
		<div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_EndDate">Конец страхования</label></div>
		<div class="span9">

			<?php
			/* @var CJuiDatePicker */
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'name' => 'order[EndDate]',
					'value' => isset($order['EndDate']) ? $order['EndDate'] : '',
					'options' => array(
						'showAnim' => '',
						//'minDate'  => '+0D',
						'dateFormat' => "yy-mm-dd"
					),
			));?>
		</div>
	</div><br/>
	<table class="table table-bordered" id="route_points_table">
		<thead>
			<tr>
				<th colspan="2">Маршрут
					<span class="pull-right">
						<?php $this->widget('bootstrap.widgets.TbButton', array(
							'buttonType' => '',
							'type' => 'primary',
							'label'=> 'Добавить промежуточную точку маршрута',
							'htmlOptions' => array('id' => 'route_point_add_button')
							))?>
					</span>
				</th>
			</tr>
		</thead>
		<tr style="display: none;" id="route_point" data-route_middle="1">
			<td class="span3"><span class="pull-right">Промежуточная точка маршрута</span></td>
			<td><?php $this->renderPartial('route_point', array('name' => 'middle', 'iteration' => '__iteration__', 'point' => array()))?></td>
		</tr>
		<tr id="route_first_point">
			<td class="span3"><span class="pull-right">Начальная точка маршрута</span></td>
			<td><?php $this->renderPartial('route_point', array('name' => 'begin', 'iteration' => false, 'point' => isset($order['route']['begin']) ? $order['route']['begin'] : array()))?></td>
		</tr>
		<?php if (isset($order['route']['middle'])) { foreach($order['route']['middle'] as $iter => $point) : ?>
			<tr data-route_middle="1">
				<td class="span3"><span class="pull-right">Промежуточная точка маршрута</span></td>
				<td><?php $this->renderPartial('route_point', array('name' => 'middle', 'iteration' => $iter, 'point' => $point))?></td>
			</tr>
		<?php endforeach; }?>
		<tr id="route_last_point">
			<td class="span3"><span class="pull-right">Конечная точка маршрута</span></td>
			<td><?php $this->renderPartial('route_point', array('name' => 'end', 'iteration' => false, 'point' => isset($order['route']['end']) ? $order['route']['end'] : array()))?></td>
		</tr>
	</table>

    <div class="row-fluid">
        <div class="span3" style="padding-left: 20px;"><label class="pull-right" for="order_EndDate">Введите текст с картинки</label></div>
        <div class="span9">

            <?if (CCaptcha::checkRequirements()) :?>
                <?php $this->widget('CCaptcha');?>
                <?=CHtml::textField('order[verifyCode]', '', array('class' => 'span12'));?>
            <?endif?>
        </div>
    </div><br/>
	<div class="row-fluid">
		<div class="span3">
			<div class="pull-right">
			<?php
				$this->widget('bootstrap.widgets.TbButton', array(
						'buttonType' => 'submit',
						'type' => 'primary',
						'label'=> 'Заказать')
				);
			?>
			</div>
		</div>
	</div>
<?php

/*
Наименование компании (текст)
	-	ИНН (текст)
	-	КПП (текст)
	-	Выгодоприобретатель (физическое или юридическое лицо, текст) (только если вид страхования - агентский)

Груз (текст)
Количество мест (число)
Единица измерения мест (выбор из справочника 1С)
Общий вес (число)
Единица измерения веса (выбор из справочника 1С)
Список документов (текст)
Начало страхования (дата, не раньше текущей даты)
Конец страхования (дата, не более чем на 60 суток больше даты начала страхования)

-	Маршрут. Содержит информацию о начальной и конечной точках маршрута,
а также (опционально) о промежуточных точках. Каждая точка - это:
o	Страна (выбор из справочника 1С)
o	Город (выбор из справочника 1С)
o	Транспорт (текст)
o	Номер транспортного средства (текст)

"NumberOfPreOrder":"000000006",
      "CompanyName":"ООО Рога и копыта",
      "Beneficiary":"",
      "Consignment":"носки,шарфы",
      "NumberOfSeat":"4",
      "NumberOfSeatMeasure":"CK",
      "Weight":"160",
      "WeightMeasure":"166",
      "Documents":"инвойс 6 от 15.12.2012 г.",
      "StartDate":"14.01.2013",
      "EndDate":"05.01.2014",
      "Transports":[
      {
            "Country":"Англия",
            "City":"Лондон",
            "Transport":"Авиамоторный",
            "RegistrationNumber":"000145"
      },
      {
            "Country":"Франция",
            "City":"Париж",
            "Transport":"Ж/д",
            "RegistrationNumber":"А4АЛВ83"
      }
      ]





*/

$this->endWidget();