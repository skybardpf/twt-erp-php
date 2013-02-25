<?php
/**
 * @var $this Counterparties_groupsController
 * @var $form TbActiveForm
 * @var $model CounterpartiesGroups
 */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/counterparties_groups'),
	'Добавление',
);
?>
<h2>Группа контрагентов<?=$parent ? (': '.$parent->name) : ''?>, Добавление группы</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error))?>