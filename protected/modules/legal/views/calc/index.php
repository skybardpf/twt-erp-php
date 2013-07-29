<?php
/**
 * User: Forgon
 * Date: 27.02.13
 *
 * @var $this CalcController
 * @var $values Calc[]
 */

$this->breadcrumbs=array(
	$this->controller_title,
);
$asset_path = CHtml::asset(Yii::app()->basePath.'/../static/select2/');
Yii::app()->clientScript->registerCssFile($asset_path.'/select2.css');
Yii::app()->clientScript->registerScriptFile($asset_path.'/select2.js');
Yii::app()->clientScript->registerScriptFile(CHtml::asset(Yii::app()->basePath.'/../static/js/calc.js'));

Yii::app()->clientScript->registerScriptFile($this->module->assets.'/js/calc/postmessage.js');
Yii::app()->clientScript->registerScriptFile(CHtml::asset(Yii::app()->basePath.'/../static/js/numeric.js'));
Yii::app()->clientScript->registerScriptFile($this->module->assets.'/js/calc/Frame.js');


?>
<h2><?=$this->controller_title?>. Шаг 1</h2>
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
<div class="form">
	<?php
	/* @var $form TbActiveForm */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'    => 'calc-form-form',
		'type'  => 'inline',
		'enableAjaxValidation' => false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	))?>

	<div class="row-fluid"><div class="span12">1. Укажите способ выбора типов товаров:</div></div>
	<div class="row-fluid">
		<div class="span12">
			<label class="radio" for="tnved_yes">
				<?=CHtml::radioButton('tnved', !isset($_POST['tnved']) || empty($_POST['tnved']) || $_POST['tnved'] == 'yes', array('value' => 'yes', 'id' => 'tnved_yes', 'data-tnved_selection' => '1'))?>
				По кодам ТНВЭД
			</label><br/>
			<label class="radio" for="tnved_no">
				<?=CHtml::radioButton('tnved', !empty($_POST['tnved']) && $_POST['tnved'] != 'yes', array('value' => 'no', 'id' => 'tnved_no', 'data-tnved_selection' => '1'))?>
				По кодам категорий
			</label>
		</div>
	</div>

	<hr>

	<div class="row-fluid"><div class="span12">2. Выберите валюту:</div></div>
	<div class="row-fluid">
		<div class="span12">
			<?= CHtml::dropDownList(
				'currency',
				(!empty($_POST['currency']) ? $_POST['currency'] : ''),
				array('' => 'Не выбрана') + Currencies::getValues())?>
		</div>
	</div>

	<hr>
    
	    <div class="row-fluid"><div class="span12">3. Введите стоимость ваших товаров:</div></div>
	    <div class="row-fluid">
		    <div class="span12">
		    <table class="table table-striped table-bordered">
			    <thead>
			        <tr>
			            <th>Категория</th>
			            <th>Стоимость</th>
			        </tr>
			    </thead>
			    <tbody>
					<tr style="display: none;" id="calc_clone_row" data-new_row="1" data-one_row="1">
						<td style="max-width: 250px;">
							<input
								type="hidden"
								name="data[new][code]"
								data-placeholder="Код ТНВЭД или наименование категории"
								data-tnved="1"
								data-init_on_clone="1"
								data-minimum_input_length="4"
								data-allow_clear="1"
								data-ajax="1"
								data-ajax_url="<?=$this->createUrl('tnved')?>">
						</td>

						<td class="span3"><input class='numeric' id='price_1' ajax_url='<?=$this->createUrl('format')?>' type="text" name="data[new][summ]" placeholder="Стоимость"></td>
					</tr>
				    <?php if ($values) :?><?php $i = 0; ?>
					    <?php foreach($values as $val) :?>
						    <tr data-one_row="1" data-new_row="0">
						        <td style="max-width: 250px;">
							        <input
								        type="hidden"
								        name="data[old_<?=$i?>][code]"
								        data-placeholder="Код ТНВЭД или наименование категории"
								        data-tnved="1"
								        data-minimum_input_length="4"
								        data-allow_clear="1"
								        data-ajax="1"
								        data-ajax_url="<?=$this->createUrl('tnved')?>"
								        value="<?=$val['code']?>">
						        </td>

							    <td><input class='numeric' id='price_2' ajax_url='<?=$this->createUrl('format')?>' type="text" name="data[old_<?=$i++?>][summ]" value="<?=$val['summ']?>" placeholder="Стоимость"></td>
						    </tr>
					    <?php endforeach; ?>
				    <?php endif; ?>
				    <tr data-one_row="1" data-new_row="1">
						<td style="max-width: 250px;">
					        <input
					        	id='ty'
						        type="hidden"
						        name="data[0][code]"
						        data-placeholder="Код ТНВЭД или наименование категории"
						        data-tnved="1"
						        data-minimum_input_length="4"
						        data-allow_clear="1"
						        data-ajax="1"
						        data-ajax_url="<?=$this->createUrl('tnved')?>">
				        </td>

				        <td class="span3"><input style='text-align: right;' class='numeric' id='price_3' ajax_url='<?=$this->createUrl('format')?>' type="text" name="data[0][summ]" placeholder="Стоимость"></td>
				    </tr>
			    </tbody>
		    </table>
		    </div>
	    </div>
    
	<div class="control-group">
		<div class="controls-row">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType' => 'submit',
					'type' => 'primary',
					'label'=> 'Рассчитать')
			);?>
		</div>
		<br/>
		<?php /*
		<div class="controls-row">
            <input type="submit" name="parse_file" value="Загрузить файл Excel для расчёта">
			<?= $form->fileField($model, 'excel_file'); ?>
		</div>
		<br/>
		<div class="controls-row"><a class="btn btn-info" href="<?=CHtml::asset(Yii::app()->basePath.'/data/primer.xlsx')?>">Скачать файл-образец</a></div>
        */ ?>

    </div>

	<?php $this->endWidget(); ?>

</div>

