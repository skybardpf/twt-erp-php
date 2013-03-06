<?php
/**
 * User: Forgon
 * Date: 27.02.13
 *
 * @var $this CalcController
 * @var $form CActiveForm
 * @var $values Calc[]
 */

$this->breadcrumbs=array(
	$this->controller_title,
);
$asset_path = CHtml::asset(Yii::app()->basePath.'/../static/select2/');
Yii::app()->clientScript->registerCssFile($asset_path.'/select2.css');
Yii::app()->clientScript->registerScriptFile($asset_path.'/select2.js');
Yii::app()->clientScript->registerScriptFile(CHtml::asset(Yii::app()->basePath.'/../static/js/calc.js'));
?>
<h2><?=$this->controller_title?></h2>

<div class="form">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'    => 'calc-form-form',
		'type'  => 'inline',
		'enableAjaxValidation' => false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	))?>
	<span class="row" style="display: none;" id="calc_clone_row" data-new_row="1" data-one_row="1">
		<span class="span5">
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
		</span>
		<span class="span3"><input type="text" name="data[new][summ]" placeholder="Стоимость"></span>
	</span>

    <fieldset>
		<?php if ($values) :?><?php $i = 0; ?>
	        <?php foreach($values as $val) :?>
	            <span class="row" data-one_row="1" data-new_row="0">
			        <span class="span5">
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
			        </span>
				    <span class="span3"><input type="text" name="data[old_<?=$i++?>][summ]" value="<?=$val['summ']?>" placeholder="Стоимость"></span>
			    </span>
		    <?php endforeach; ?>
        <?php endif; ?>
        <span class="row" data-one_row="1" data-new_row="1">
	        <span class="span5">
		        <input
                        type="hidden"
                        name="data[0][code]"
                        data-placeholder="Код ТНВЭД или наименование категории"
                        data-tnved="1"
                        data-minimum_input_length="4"
                        data-allow_clear="1"
                        data-ajax="1"
                        data-ajax_url="<?=$this->createUrl('tnved')?>">
	        </span>
		    <span class="span3"><input type="text" name="data[0][summ]" placeholder="Стоимость"></span>
	    </span>
    </fieldset>
	<div class="control-group">
		<div class="controls-row">
            <input type="submit" name="parse_file" value="Загрузить файл Excel для расчёта">
			<?= $form->fileField($model, 'excel_file'); ?>
		</div>
		<div class="controls-row"><a class="btn btn-info" href="<?=CHtml::asset(Yii::app()->basePath.'/data/primer.xlsx')?>">Скачать файл-образец</a></div>
        <div class="controls-row">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label'=> 'Рассчитать')
			);?>

        </div>
    </div>

	<?php $this->endWidget(); ?>

</div>

