<?php
/* @var $this EntitiesController */

$this->breadcrumbs=array(
	'Юридические лица' => $this->createUrl('/legal/entities/'),
	'Добавление Юр.Лица',
);
?>
<h2>Добавление Юр.Лица</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>