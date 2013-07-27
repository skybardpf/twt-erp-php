<?php
/**
 * @var $this Counterparties_groupsController
 * @var $form TbActiveForm
 * @var $model CounterpartiesGroups
 */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/pegroup'),
	'Добавление',
);
?>
<h2>Группа физ.лиц<?=$parent ? (': '.$parent->name) : ''?>, Добавление группы</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error))?>