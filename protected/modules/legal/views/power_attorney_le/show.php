<?php
/* @var $this Power_attorney_leController */
/* @var $model PowerAttorneysLE */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Просмотр',
);
?>
<h2><?=$model->name?></h2>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'name',         'label' => 'Название'),
			array('name' => 'date',         'label' => 'Загружен'),
			array('name' => 'loaded',       'label' => 'Дата загрузки'),
			array('name' => 'expire',       'label' => 'Срок действия'),
			array('name' => 'break',        'label' => 'Истекает'),
			array('name' => 'typ_doc',      'label' => 'Вид доверенности'),
			array('name' => 'nom',          'label' => 'Номер документа'),
		)
	));
	?>
</div>
