<?php
/**
 * @var $this  IndividualsController
 * @var $model Individuals
 * @var $form  TbActiveForm
 */

if ($error) echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div'); ?>

<div class="form">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'    => 'model-form-form',
		'type'  => 'horizontal',
		'enableAjaxValidation' => false,
	))?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Сохранить')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'    => 'link',
		'label'         => 'Отмена',
		'url'           => ($model->getprimaryKey()
								? $this->createUrl('view', array('id' => $model->getprimaryKey()))
								: $this->createUrl('index')
							)
	)); ?>
    
	<?=$form->errorSummary($model)?>

	<fieldset>
		<?= $form->textFieldRow(    $model, 'family',      array('class' => 'span6')); ?>
        <?= $form->textFieldRow(    $model, 'name',        array('class' => 'span6')); ?>
        <?= $form->textFieldRow(    $model, 'parent_name', array('class' => 'span6')); ?>
        <?= $form->dropDownListRow( $model, 'citizenship', Countries::getValues()); ?>
        <div class="control-group">
            <label class="control-label" for="Individuals_birth_date"><?php echo $model->getAttributeLabel("birth_date"); ?></label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model'     => $model,
                        'attribute' => 'birth_date',
                        // additional javascript options for the date picker plugin
                        'options'   =>array(
                            'showAnim'   =>'fold',
                            'dateFormat' => 'yy-mm-dd'
                        ),
                        'htmlOptions' => array(
                            'class' => 'some_class',
                            'style' => 'height:20px;'
                        ),
                    )); ?>
            </div>
        </div>
        <?= $form->textFieldRow(    $model, 'birth_place',  array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'phone',        array('class' => 'span6')); ?>
		<?= $form->textFieldRow(    $model, 'email',        array('class' => 'span6')); ?>
		<?= $form->textAreaRow(     $model, 'adres'); ?>
		<?= $form->textFieldRow(    $model, 'ser_nom_pass', array('class' => 'span6')); ?>
		<?php /* date_pass */ ?>
		<div class="control-group">
			<label class="control-label" for="Individuals_date_passrf"><?php echo $model->getAttributeLabel("date_pass"); ?></label>
			<div class="controls">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'model' => $model,
					'attribute' => 'date_pass',
					// additional javascript options for the date picker plugin
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat' => 'yy-mm-dd'
					),
					'htmlOptions' => array(
						'class' => 'some_class',
						'style'=>'height:20px;'
					),
				)); ?>
			</div>
		</div>
		<?= $form->textFieldRow(    $model, 'organ_pass',   array('class' => 'span6')); ?>
		<?php /* date_exp_pass */?>
		<div class="control-group">
			<label class="control-label" for="Individuals_date_exp_pass"><?php echo $model->getAttributeLabel("date_exp_pass"); ?></label>
			<div class="controls">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'model' => $model,
					'attribute' => 'date_exp_pass',
					// additional javascript options for the date picker plugin
					'options'=>array(
						'showAnim'=>'fold',
						'dateFormat' => 'yy-mm-dd'
					),
					'htmlOptions' => array(
						'class' => 'some_class',
						'style'=>'height:20px;'
					),
				)); ?>
			</div>
		</div>
		<?php /*= $form->textFieldRow(    $model, 'date_pass',    array('class' => 'span6')); */?>
        <?php /*
			    • id - идентификатор (строка) (параметром в урле)
				• date_pass - ПаспортРФ.ДатаВыдачи (дата)
				• citizenship - Гражданство.Страна.Наименование (Строка)
				• fullname – Наименование (строка)
				• ser_nom_pass - Паспорт.СерияНомер (строка)
				• phone – Телефон (строка)
				• family – Фамилия (строка)
				• birth_date – ДатаРождения (дата)
				• organ_passrf - ПаспортРФ.КемВыдан (строка)
				• date_exp_pass - Паспорт.СрокОкончания (дата)
				• date_exp_passrf - ПаспортРФ.СрокОкончания (дата)
				• birth_place – МестоРождения (строка)
				• group_code - Родитель.Код (строка)
				• parent_name – ФИО.Отчество (строка)
				• email – АдресЭлектроннойПочты (строка)
				• ser_nom_passrf - ПаспортРФ.СерияНомер (строка)
				• name – Имя (строка)
				• organ_pass - Паспорт.КемВыдан (строка)
				• date_passrf - ПаспортРФ.ДатаВыдачи (дата)
				• deleted – признак пометки на удаление (булево)

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
		array('name' => 'email',            'label' => 'E-mail'),--> */?>
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