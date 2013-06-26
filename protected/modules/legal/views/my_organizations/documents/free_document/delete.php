<?php
/**
 * @var $this   My_OrganizationsController
 * @var $doc    FreeDocument
 */

/*$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Удаление',
);*/

$this->beginContent('/my_organizations/show');
?>
Вы действительно хотите <?=$doc->deleted ? '<b>восстановить учредительный документ</b>': '<b>удалить учредительный документ</b>'?> «<?=CHtml::encode($doc->name)?>»?

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'   => 'news-delete-form',
	'type' => 'horizontal',
))?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'type'       => 'danger',
	'label'      => 'Да',
	'htmlOptions' => array('name' => 'result', 'value' => 'yes')
)); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'submit',
	'type'       => 'success',
	'label'      => 'Нет',
	'htmlOptions' => array('name' => 'result', 'value' => 'no')
)); ?>
<?php
$this->endWidget();
$this->endContent();
?>