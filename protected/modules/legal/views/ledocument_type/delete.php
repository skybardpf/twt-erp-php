<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities*/

$this->breadcrumbs=array(
	'Тип документа' => array($this->createUrl('/legal/ledocument_type/')),
	'Удаление',
);?>
Вы действительно хотите удалить тип документа «<?=CHtml::encode($model->name_of_doc)?>»?

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