<?php
/**
 * @var $this EntitiesController
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
		<?= $form->textFieldRow(    $model, 'name',         array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'full_name',    array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'eng_name',     array('class' => 'span6')); ?>
		<?= $form->dropDownListRow( $model, 'country', array('' => 'не задано')+Countries::getValues(), array('class' => 'span6')); ?>
		<?= $form->dropDownListRow( $model, 'type_no_res', array('' => 'Резидент РФ')+$model->NonResidentValues, array('class' => 'span6')); ?>
		<?= $form->dropDownListRow( $model, 'parent', array('' => 'не задано')+CounterpartiesGroups::getValues(), array('class' => 'span6')); ?>
		<?= $form->textAreaRow(     $model, 'comment',      array('class'=>'span6', 'rows'=>5)); ?>
		<?= $form->textFieldRow(    $model, 'inn',          array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'kpp',          array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'ogrn',         array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'yur_address',  array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'fact_address', array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'reg_nom',      array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'sert_nom',     array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'sert_date',    array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'vat_nom',      array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'profile',      array('class' => 'span6')); ?>
<!--
		array('name' => 'family',           'label' => 'Фамилия'),
		array('name' => 'name',             'label' => 'Имя'),
		array('name' => 'parent_name',      'label' => 'Отчество'),
		array('name' => 'ser_nom_pass',     'label' => 'Серия-номер паспорта'),
		array('name' => 'date_pass',        'label' => 'Дата выдачи паспорта'),
		array('name' => 'organ_pass',       'label' => 'Орган, выдавший паспорт'),
		array('name' => 'date_exp_pass',    'label' => 'Срок действия паспорта'),
		array('name' => 'ser_nom_passrf',   'label' => 'Серия-номер паспорта РФ'),
		array('name' => 'date_passrf',      'label' => 'Дата выдачи паспорта РФ'),
		array('name' => 'organ_passrf',     'label' => 'Орган, выдавший паспорт РФ'),
		array('name' => 'date_exp_passrf',  'label' => 'Срок действия паспорта РФ'),
		array('name' => 'group_code',       'label' => 'Группа физ.лиц'),
		array('name' => 'phone',            'label' => 'Номер телефона'),
		array('name' => 'adres',            'label' => 'Адрес'),
		array('name' => 'email',            'label' => 'E-mail'),-->
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