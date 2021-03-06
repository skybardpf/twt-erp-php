<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities*/

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/ledocument_type/'),
	'Удаление',
);?>
Вы действительно хотите <?=$model->deleted ? '<b>восстановить тип документа</b>': '<b>удалить тип документа</b>'?> «<?=CHtml::encode($model->name_of_doc)?>»?
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