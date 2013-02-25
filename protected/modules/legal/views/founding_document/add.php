<?php
/* @var $this EntitiesController */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/founding_document/'),
	'Добавление',
);
?>
<h2>Добавление учредительного документа</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>