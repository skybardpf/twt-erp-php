<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities */
/* @var $form TbActiveForm */
?>

<div class="form">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'    => 'model-form-form',
		'type'  => 'horizontal',
		'enableAjaxValidation' => false,
	))?>

	<?=$form->errorSummary($model)?>

	<fieldset>
		<?= $form->textFieldRow($model, 'name', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'full_name', array('class' => 'span6')); ?>
		<?= $form->dropDownListRow($model, 'country', array('' => 'не заданно'), array('class' => 'span6')); ?>
		<?= $form->checkBoxRow($model, 'resident'); ?>
		<?= $form->dropDownListRow($model, 'type_no_res', array('' => 'не заданно'), array('class' => 'span6')); ?>
		<?= $form->checkBoxRow($model, 'contragent'); ?>
		<?= $form->dropDownListRow($model, 'group_name', array('' => 'не заданно'), array('class' => 'span6')); ?>
		Сокращенное наименование (текст, обязательное);
		<?= $form->textAreaRow($model, 'comment', array('class'=>'span6', 'rows'=>5)); ?>
		<?= $form->textFieldRow($model, 'inn', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'kpp', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'ogrn', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'yur_address', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'fact_address', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'reg_nom', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'sert_nom', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'sert_date', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'vat_nom', array('class' => 'span6')); ?>
		<?= $form->textFieldRow($model, 'profile', array('class' => 'span6')); ?>
	</fieldset>
	<div class="control-group ">
		<div class="controls">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label'=> (!$model->getprimaryKey() ? 'Добавить' : 'Сохранить'))
		);?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div>