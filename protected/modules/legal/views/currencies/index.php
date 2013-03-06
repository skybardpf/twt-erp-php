<?php
/* @var $this Counterparties_groupsController */

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
				array('name'=>'id', 'header'=>'#'),
				array('name'=>'name', 'header'=>'Название'),
			),
		));
	} else {
		echo 'Ни одной валюты не зарегистрировано.';
	}
	?>
</div>
