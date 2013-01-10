<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities */

$this->breadcrumbs=array(
	'Юридические лица' => array($this->createUrl('/legal/entities/')),
	'Редактирование',
);
?>
<h1><?=$model->full_name?></h1>
<?php $this->renderPartial('form', array('model' => $model)) ?>
