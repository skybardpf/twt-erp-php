<?php
/**
 *  Документы -> Свободный документ. Удаление.
 *  User: Skibardin A.A.
 *  Date: 26.06.13
 *
 *  @var $this   My_OrganizationsController
 *  @var $doc    FreeDocument
 */
$this->beginContent('/my_organizations/show');
?>
Вы действительно хотите <?=$doc->deleted ? '<b>восстановить свободный документ</b>': '<b>удалить свободный документ</b>'?> «<?=CHtml::encode($doc->name)?>»?

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