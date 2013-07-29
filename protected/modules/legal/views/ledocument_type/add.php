<?php
/* @var $this EntitiesController */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/ledocument_type/'),
	'Добавление',
);
?>
<h2>Добавление типа документа</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>