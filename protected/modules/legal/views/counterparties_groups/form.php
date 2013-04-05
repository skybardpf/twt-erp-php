<?php
/**
 * User: Forgon
 * Date: 22.02.13
 *
 * @var $this Counterparties_groupsController
 * @var $model LegalEntities
 * @var $form TbActiveForm
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
		<?= $form->textFieldRow($model, 'name', array('class' => 'span6')); ?>
		<?= $form->dropDownListRow($model, 'parent', array('' => 'Не выбран')+$model->getParentValues()); ?>
    </fieldset>
    <div class="control-group ">
        <div class="controls">
			<?php
	        $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label'=> (!$model->getprimaryKey() ? 'Добавить' : 'Сохранить')
			));
	        echo '&nbsp;';
	        $this->widget('bootstrap.widgets.TbButton', array(
		        'url' => $this->createUrl('index'),
		        'buttonType' => '',
		        'type' => '',
		        'label'=> 'Отмена')
	        );
	        ?>
        </div>
    </div>

	<?php $this->endWidget(); ?>

</div>