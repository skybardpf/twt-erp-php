<?php
/**
 * @var $this EntitiesController
 * @var $model LegalEntities
 * @var $form TbActiveForm
 * @var $error string
 */
// ТУДУ Редактирование типов документов
// Внимание не доделан тип документа

if ($error) echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div'); ?>

<div class="form">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'    => 'model-form-form',
		'type'  => 'horizontal',
		'enableAjaxValidation' => false,
	))?>

	<?=$form->errorSummary($model)?>

	<fieldset>
		<?= $form->textFieldRow(    $model, 'name_of_doc',      array('class' => 'span6')); ?>
		<?php if ($countries = $model->list_of_countries) { foreach($countries as $c) {

		} }?>
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