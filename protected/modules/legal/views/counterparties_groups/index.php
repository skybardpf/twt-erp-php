<?php
/* @var $this Counterparties_groupsController */

$this->breadcrumbs=array(
	'Группы контрагентов',
);
?>
<h2>Группы контрагентов<?=$parent ? (': '.$parent->name) : ''?></h2>
<?php if ($parent) :?><a href="<?=$this->createUrl('index', ($parent ? array('pid' => $parent->parent) : array()))?>"><?=$parent->name?></a><?php endif; ?>
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
			'columns'=>array(
				array('name'=>'id', 'header'=>'#'),
				array('name'=>'name', 'header'=>'Название', /*'type' => 'raw', 'value' => 'CHtml::link($data->name, Yii::app()->controller->createUrl("index", array("pid" => $data->id)))'*/),
				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
					//'class' => 'zii.widgets.grid.CButtonColumn',
					'template' => '{update} {delete}',
					//'viewButtonLabel' => null,
					//'viewButtonImageUrl' => null,
					//'viewButtonIcon' => false
					//'htmlOptions'=>array('style'=>'width: 50px'),
				),
			),
		));
	} else {
		echo 'Ни одной группы контрагентов не зарегистрировано.';
	}?>
</div>
<a class="btn btn-success" href="<?=$this->createUrl('add', ($parent ? array('pid' => $parent->getprimaryKey()) : array()))?>">Добавить Группу контрагентов</a>