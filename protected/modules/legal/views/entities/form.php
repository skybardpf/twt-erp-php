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

	<div class="form-actions">
		<?php
		$buttons = $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label'=> (!$model->getprimaryKey() ? 'Добавить' : 'Сохранить')), true
		);
		echo $buttons;
		?>
	</div>

	<fieldset>
		<?= $form->textFieldRow($model, 'full_name', array('class' => 'input-xxlarge')); ?>
		<?= $form->dropDownListRow($model, 'country', array('1' => 'one')); ?>
		<?= $form->checkBoxRow($model, 'resident'); ?>
		<?= $form->dropDownListRow($model, 'type_no_res', array('1' => 'one')); ?>
		<?= $form->checkBoxRow($model, 'contragent'); ?>
		<?= $form->dropDownListRow($model, 'group_name', array('1' => 'one')); ?>
		Сокращенное наименование (текст, обязательное);
		<?= $form->textAreaRow($model, 'comment', array('class'=>'span8', 'rows'=>5)); ?>
		<?= $form->textFieldRow($model, 'inn', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'kpp', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'ogrn', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'yur_address', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'fact_address', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'reg_nom', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'sert_nom', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'sert_date', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'vat_nom', array('class' => 'input-xxlarge')); ?>
		<?= $form->textFieldRow($model, 'profile', array('class' => 'input-xxlarge')); ?>
	</fieldset>

	<div class="form-actions"><?=$buttons?></div>

	<?php $this->endWidget(); ?>

</div>