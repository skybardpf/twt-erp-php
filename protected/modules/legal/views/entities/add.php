<?php
/* @var $this EntitiesController */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Добавление',
);
?>
<h2>Добавление Юр.Лица</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>