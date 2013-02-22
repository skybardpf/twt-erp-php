<?php
/* @var $this Counterparties_groupsController */

$this->breadcrumbs=array(
	'Группы контрагентов' => array('/legal/counterparties_groups'),
	'Редактирование',
);
?>
<h2>Группа контрагентов <?=$model->name?>: редактирование</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error))?>
