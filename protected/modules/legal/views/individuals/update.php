<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities */

/*$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Редактирование',
);*/
?>
<h2>Редактирование физического лица</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>
