<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities*/

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Удаление',
);?>
Вы действительно хотите <?=$model->deleted ? '<b>восстановить Физ.лицо</b>': '<b>удалить Физ.лицо</b>'?> «<?=CHtml::encode($model->fullname)?>»?

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