<?php
/* @var $this EntitiesController */
/* @var $model LegalEntities */

$this->breadcrumbs=array(
	'Юридические лица' => array($this->createUrl('/legal/entities/')),
	'Редактирование',
);
?>
<h2><?=$model->full_name?></h2>
<?php $this->renderPartial('form', array('model' => $model)) ?>
