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
	    <?= $form->dropDownListRow($model, 'id', array('' => 'Проблемные лица'), array('class' => 'span6') + ($model->new ? array() : array('disabled' => 'disabled')))?>
	    <?= $form->dropDownListRow($model, 'id_yur', LegalEntities::$values, array('class' => 'span6') + ($model->new ? array() : array('disabled' => 'disabled')))?>
	    <?= $form->dropDownListRow($model, 'role', array('' => 'Непонятные роли'), array('class' => 'span6') + ($model->new ? array() : array('disabled' => 'disabled')))?>
	    <?= $form->dropDownListRow($model, 'vid', array('' => 'Непонятные виды'), array('class' => 'span6') + ($model->new ? array() : array('disabled' => 'disabled'))); ?>

	    <?= $form->textFieldRow($model, 'cost', array('class' => 'span6')); ?>
	    <?= $form->textFieldRow($model, 'percent', array('class' => 'span6')); ?>
	    <?= $form->dropDownListRow($model, 'cur', array('' => 'Не выбрана') + Currencies::$values, array('class' => 'span6')); ?>
	    <?= $form->textFieldRow($model, 'add_info', array('class' => 'span6')); ?>

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
	        );?>
        </div>
    </div>

	<?php $this->endWidget(); ?>

</div>