<?php
/* @var $this Counterparties_groupsController */

$this->breadcrumbs=array(
	$this->controller_title,
);
PEGroup::getValues();
?>
<h2><?=$this->controller_title?><?=$parent ? (': '.$parent->name) : ''?></h2>
<?php if ($parent) :?><a href="<?=$this->createUrl('index', ($parent ? array('pid' => $parent->parent) : array()))?>"><?=$parent->name?></a><?php endif; ?>
<div>
	<?php
	if ($elements) {
		$gridDataProvider = new CArrayDataProvider($elements);

		$this->widget('bootstrap.widgets.TbGridView', array(
			'type'=>'striped',
			'dataProvider' => $gridDataProvider,
			'columns'=>array(
				array('name'=>'id', 'header'=>'#'),
				array('name'=>'name', 'header'=>'Название',),
				array('name' => 'parent', 'header' => 'Родительская группа', 'type' => 'raw', 'value' => 'isset(PEGroup::$values[$data["parent"]]) ? PEGroup::$values[$data["parent"]] : "Не установлена или удалена"'),
				array(
					'class'=>'bootstrap.widgets.TbButtonColumn',
					'template' => '{update} {delete}',
				),
			),
		));
	} else {
		echo 'Ни одной группы физ.лиц не зарегистрировано.';
	}?>
</div>
<a class="btn btn-success" href="<?=$this->createUrl('add', ($parent ? array('pid' => $parent->getprimaryKey()) : array()))?>">Добавить Группу физ.лиц</a>