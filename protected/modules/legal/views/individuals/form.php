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

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Сохранить')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Отмена')); ?>
    
	<?=$form->errorSummary($model)?>

	<fieldset>
		<?= $form->textFieldRow(    $model, 'family',         array('class' => 'span6')); ?>
        <?= $form->textFieldRow(    $model, 'name',         array('class' => 'span6')); ?>
        <?= $form->textFieldRow(    $model, 'parent_name',         array('class' => 'span6')); ?>
        <?= $form->dropDownListRow($model, 'citizenship', $countries); ?>
        <div class="control-group">
            <label class="control-label" for="Individuals_date_of_birth"><?php echo $model->getAttributeLabel("date_of_birth"); ?></label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model' => $model,
                        'attribute' => 'date_of_birth',
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
        <?= $form->textFieldRow(    $model, 'place_of_birth',         array('class' => 'span6')); ?>
        <?= $form->textAreaRow($model, 'adres'); ?>
        <?= $form->textFieldRow(    $model, 'ser_nom_pass',         array('class' => 'span6')); ?>
        <?= $form->textFieldRow(    $model, 'date_pass',         array('class' => 'span6')); ?>
        <?= $form->textFieldRow(    $model, 'organ_pass',         array('class' => 'span6')); ?>
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