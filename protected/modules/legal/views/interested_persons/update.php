<?php
/* @var $this Counterparties_groupsController */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/pegroup'),
	'Редактирование',
);
?>
<h2>Заинтересованное лицо <?=$model->role?>: редактирование</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error))?>
