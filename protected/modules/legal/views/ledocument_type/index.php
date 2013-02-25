<?php
/* @var $this BanksController */

$this->breadcrumbs=array(
	$this->controller_title,
);
?>
<h2><?=$this->controller_title?></h2>
<div>
	<?php
	if ($elements) {
		$gridDataProvider = new CArrayDataProvider($elements);

		$this->widget('bootstrap.widgets.TbGridView', array(
			'type'=>'striped',
			'dataProvider' => $gridDataProvider,
			'columns'=>array(
				array('name' => 'id', 'header'=>'#'),
				array('name' => 'name_of_doc', 'header'=>'Название',),
				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
				),
			),
		));
	} else {
		echo 'Ни одного типа документов не зарегистрировано.';
	}
	?>
</div>
<a class="btn btn-success" href="<?=$this->createUrl('add')?>">Добавить тип документа</a>