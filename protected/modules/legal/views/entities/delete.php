<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities*/

$this->breadcrumbs=array(
	'Юридические лица' => array($this->createUrl('/legal/entities/')),
	'Пометка об удалении',
);?>
Вы действительно хотите <?=$model->deleted ? '<b>снять пометку удаления со структуры</b>': '<b>пометить на удаление структуру</b>'?> «<?=CHtml::encode($model->full_name)?>»?

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