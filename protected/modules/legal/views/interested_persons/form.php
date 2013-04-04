<?php
/**
 * User: Forgon
 * Date: 22.02.13
 *
 * @var $this Interested_personsController
 * @var $model InterestedPerson
 * @var $form TbActiveForm
 */
LegalEntities::getValues();
Currencies::getValues();
if ($error) echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div'); ?>

<div class="form">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'    => 'model-form-form',
	'type'  => 'horizontal',
	'enableAjaxValidation' => false,
))?>
	<?=$form->errorSummary($model)?>

    <fieldset>
		<?php if ($model->new) :?>

	    <?php endif;?>
	    <?= 1//$form->dropDownListRow($model, 'id', )?>
	    <?= $form->dropDownListRow($model, 'id_yur', LegalEntities::$values, $model->new ? array() : array('disabled' => 'disabled'))?>

	    <?= $form->textFieldRow($model, 'cost', array('class' => 'span6')); ?>
	    <?= $form->textFieldRow($model, 'percent', array('class' => 'span6')); ?>
	    <?= $form->textFieldRow($model, 'vid', array('class' => 'span6')); ?>
	    <?= $form->dropDownListRow($model, 'cur', array('' => 'Не выбрана')+Currencies::$values); ?>
	    <?= $form->textFieldRow($model, 'add_info', array('class' => 'span6')); ?>
		<?= $form->dropDownListRow($model, 'parent', array('' => 'Не выбран')+$model->getParentValues()); ?>

	    <!--'id'            => 'Лицо',
	    'role'          => 'Роль',
	    'add_info'      => 'Дополнительные сведения',
	    'cost'          => 'Номинальная стоимость пакета акций',
	    'percent'       => 'Величина пакета акций',
	    'vid'           => 'Вид лица', // (выбор из справочника юр. лиц или физ. лиц, обязательное); Физические лица
	    'cur'           => 'Валюта номинальной стоимости',
	    'deleted'       => 'Удален',
	    'id_yur'        => 'Юр.Лицо'-->
    </fieldset>
    <div class="control-group ">
        <div class="controls">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'label'=> (!$model->getprimaryKey() ? 'Добавить' : 'Сохранить')
		));?>
        </div>
    </div>

	<?php $this->endWidget(); ?>

</div>