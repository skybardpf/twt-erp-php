<?php
/* @var $this Counterparties_groupsController */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/pegroup'),
	'Редактирование',
);
?>
<h2>Группа физ.лиц <?=$model->name?>: редактирование</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error))?>
