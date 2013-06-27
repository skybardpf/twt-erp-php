<?php
/**
 *  Банковские счета -> Форма редактирования банковского счета.
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this       My_OrganizationsController
 *  @var $model      SettlementAccount
 */
?>
Вы действительно хотите <?=$model->deleted ? '<b>восстановить банковский счет</b>': '<b>удалить банковский счет</b>'?> «<?=CHtml::encode($model->name)?>»?

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'news-delete-form',
	'type'=>'horizontal',
))?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'=> 'submit',
	'type'      => 'danger',
	'label'     => 'Да',
	'htmlOptions' => array('name' => 'result', 'value' => 'yes')
)); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'=> 'submit',
	'type'      => 'success',
	'label'     => 'Нет',
	'htmlOptions' => array('name' => 'result', 'value' => 'no')
)); ?>
<?php $this->endWidget(); ?>