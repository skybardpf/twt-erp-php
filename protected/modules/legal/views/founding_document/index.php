<?php
/* @var $this BanksController */

$this->breadcrumbs=array(
	'Учредительные документы',
);
?>
<h2>Учредительные документы</h2>
<div>
	<?php
	if ($elements) {
		$gridDataProvider = new CArrayDataProvider($elements);

		$this->widget('bootstrap.widgets.TbGridView', array(
			'type'=>'striped',
			'dataProvider' => $gridDataProvider,
			'columns'=>array(
				array('name' => 'id', 'header'=>'#'),
				array('name' => 'name', 'header'=>'Название',),
				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
				),
			),
		));
	} else {
		echo 'Ни одного учредительного документа не зарегистрировано.';
	}
	?>
</div>
