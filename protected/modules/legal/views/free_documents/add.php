<?php
/* @var $this EntitiesController */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/free_document/'),
	'Добавление',
);
?>
<h2>Добавление свободного документа</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>