<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */

/* @var $this Power_attorney_leController */
/* @var $elements array */

$this->breadcrumbs=array(
	$this->controller_title,
);
?>
<h2><?=$this->controller_title?></h2>
<?php
if ($elements) {
	$gridDataProvider = new CArrayDataProvider($elements);

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'=>'striped',
		'dataProvider' => $gridDataProvider,
		'columns'=>array(
			array('name'=>'id', 'header'=>'#'),
			array('name'=>'name', 'header'=>'Название'),
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
			),
		),
	));
} else {
	echo 'Ни одной доверенности не зарегистрировано.';
}
?>

<a class="btn btn-success" href="<?=$this->createUrl('add')?>">Добавить доверенность</a>
