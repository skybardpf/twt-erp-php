<?php
/**
 * @var $this Power_attorney_leController
 * @var $model PowerAttorneysLE
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
		<?= $form->textFieldRow(    $model, 'name',         array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'date',    array('class' => 'span6')); ?>
		<?= $form->dropDownListRow( $model, 'id_yur', array('' => 'не задано') + LegalEntities::getValues(), array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'nom',     array('class' => 'span6')); ?>
		<?= $form->dropDownListRow( $model, 'typ_doc', array('' => 'Непонятные типы доверенностей'), array('class' => 'span6')); ?>
		<?= $form->dropDownListRow( $model, 'id_lico', array('' => 'Непонятные лица'), array('class' => 'span6')); ?>
		<?= $form->textFieldRow(     $model, 'loaded',      array('class'=>'span6')); ?>
		<?= $form->textFieldRow(    $model, 'expire',          array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'break',          array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'e_ver',         array('class' => 'span6')); ?>
		<!--id:,
		name: rt300000002,
		date: 2013-11-25,
		from_user:true,
		user: Главбух,
		id_yur: 1000000005,
		nom:75,
		typ_doc: Генеральная,
		id_lico: 0000000001,
		loaded: 2013-11-25,
		expire: 2013-11-25,
		break: 2013-11-25,
		e_ver: rt34000000002,
		contract_types: [100432, 030432, 005432],
		scans: [ 00432, 00432, 00432]-->
	</fieldset>
	<div class="control-group ">
		<div class="controls">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label'=> (!$model->getprimaryKey() ? 'Добавить' : 'Сохранить'))
			);
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