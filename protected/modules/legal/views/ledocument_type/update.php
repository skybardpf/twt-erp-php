<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities */

$this->breadcrumbs=array(
	'Тип документа' => $this->createUrl('/legal/entities/'),
	'Редактирование типа документа',
);
?>
<h2><?=$model->full_name?></h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error)) ?>
