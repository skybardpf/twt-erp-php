<?php
/* @var $this DdsarticlesController */

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
				array('name'=>'name', 'header'=>'Название'),
			),
		));
	} else {
		echo 'Ни одной статьи движения денежных стредств не зарегистрировано.';
	}
	?>
</div>
