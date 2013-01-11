<?php
/* @var $this Counterparties_groupsController */

$this->breadcrumbs=array(
	'Страны юрисдикции',
);
?>
<h2>Страны юрисдикции</h2>
<div>
	<?php
	if ($elements) {
		$gridDataProvider = new CArrayDataProvider($elements);

		$this->widget('bootstrap.widgets.TbGridView', array(
			'type'=>'striped',
			'dataProvider' => $gridDataProvider,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'#'),
				array('name'=>'name', 'header'=>'Название')
			),
		));
	} else {
		echo 'Ни одной Страны юрисдикции не зарегистрировано.';
	}
	?>
</div>
