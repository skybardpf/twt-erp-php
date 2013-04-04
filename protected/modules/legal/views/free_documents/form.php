<?php
/**
 * @var $this Free_documentsController
 * @var $model FreeDocument
 * @var $form TbActiveForm
 * @var $error string
 */

if ($error) echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div'); ?>

<div class="form">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'    => 'model-form-form',
		'type'  => 'horizontal',
		'enableAjaxValidation' => false,
	))?>

	<?=$form->errorSummary($model)?>

	<fieldset>
		<?= $form->textFieldRow(    $model, 'name',         array('class' => 'span6')); ?>
		<?= $form->dropDownListRow( $model, 'id_yur', array('' => 'не задано')+LegalEntities::getValues(), array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'date'); ?>
		<?= $form->textFieldRow(    $model, 'expire'); ?>
		<?= $form->textFieldRow( $model, 'nom', array('class' => 'span6')); ?>
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