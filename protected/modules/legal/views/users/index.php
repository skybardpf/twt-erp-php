<?php
/* @var $this Counterparties_groupsController */

$this->breadcrumbs=array(
	'Пользователи',
);
?>
<h2>Пользователи</h2>
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
				array('name'=>'name', 'header'=>'ФИО'),
			),
		));
	} else {
		echo 'Ни одного пользователя не зарегистрировано.';
	}
	?>
</div>
