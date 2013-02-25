<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/free_document/'),
	'Редактирование',
);
?>
<h2><?=$model->name?></h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>
