<?php
/* @var $this Power_attorney_leController */
/* @var $model PowerAttorneysLE */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/counterparties_groups/'),
	'Удаление',
);?>
Вы действительно хотите <?=$model->deleted ? '<b>восстановить доверенность</b>': '<b>удалить доверенность</b>'?> «<?=CHtml::encode($model->name)?>»?

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'news-delete-form',
	'type'=>'horizontal',
))?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'=>'submit',
	'type'=>'danger',
	'label'=>'Да',
	'htmlOptions' => array('name' => 'result', 'value' => 'yes')
)); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'=>'submit',
	'type'=>'success',
	'label'=>'Нет',
	'htmlOptions' => array('name' => 'result', 'value' => 'no')
)); ?>
<?php $this->endWidget(); ?>