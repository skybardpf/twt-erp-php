<?php
/* @var $this EntitiesController */

$this->breadcrumbs=array(
	'Тип документа' => $this->createUrl('/legal/ledocument_type/'),
	'Добавление типа документа',
);
?>
<h2>Добавление типа документа</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>