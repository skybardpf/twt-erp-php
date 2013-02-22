<?php
/**
 * User: Forgon
 * Date: 22.02.13
 *
 * @var $this Counterparties_groupsController
 * @var $model LegalEntities
 * @var $form TbActiveForm
 */
?>
<?php if ($error) :?>
	<?=CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div')?>
<?php endif; ?>

<div class="form">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'    => 'model-form-form',
	'type'  => 'horizontal',
	'enableAjaxValidation' => false,
))?>
	<?=$form->errorSummary($model)?>

    <fieldset>
		<?= $form->textFieldRow($model, 'name', array('class' => 'span6')); ?>
		<?= $form->dropDownListRow($model, 'parent', array('' => 'Не выбран')+$model->getParentValues()); ?>
    </fieldset>
    <div class="control-group ">
        <div class="controls">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'label'=> 'Добавить'
		));?>
        </div>
    </div>

	<?php $this->endWidget(); ?>

</div>