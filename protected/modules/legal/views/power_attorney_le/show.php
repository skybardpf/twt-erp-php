<?php
/* @var $this Power_attorney_leController */
/* @var $model PowerAttorneysLE */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Просмотр',
);
?>
<h2>Доверенность "<?=$model->name?>"</h2>
<a href="<?=$this->createUrl('index')?>">Назад к списку</a>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'id_lico',      'label' => 'Лицо'),
			array('name' => 'name',         'label' => 'Название'),
			array('name' => 'date',         'label' => 'Дата доверенности'),
			array('name' => 'loaded',       'label' => 'Дата загрузки документа'),
			array('name' => 'expire',       'label' => 'Срок действия'),
			array('name' => 'break',        'label' => 'Дата отмены'),
			array('name' => 'typ_doc',      'label' => 'Вид доверенности'),
			array('name' => 'nom',          'label' => 'Номер документа'),
		)
	));
	?>
</div>
