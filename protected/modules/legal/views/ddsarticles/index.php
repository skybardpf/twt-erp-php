<?php
/* @var $this Counterparties_groupsController */

$this->breadcrumbs=array(
	'Статьи движения денежных стредств',
);
?>
<h2>Статьи движения денежных стредств</h2>
<div>
	<?php
	if ($elements) {
		/*$gridDataProvider = new CArrayDataProvider(array(
			array('id'=>1, 'firstName'=>'Mark', 'lastName'=>'Otto', 'language'=>'CSS'),
			array('id'=>2, 'firstName'=>'Jacob', 'lastName'=>'Thornton', 'language'=>'JavaScript'),
			array('id'=>3, 'firstName'=>'Stu', 'lastName'=>'Dent', 'language'=>'HTML'),
		));*/
		$gridDataProvider = new CArrayDataProvider($elements);

		$this->widget('bootstrap.widgets.TbGridView', array(
			'type'=>'striped',
			'dataProvider' => $gridDataProvider,
			'template'=>"{items}",
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
