<?php
/* @var $this EntitiesController */
/* @var $elements array */

$this->breadcrumbs=array(
	'Юридические лица',
);
?>
<h2>Юридические лица</h2>
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
			array('name'=>'name', 'header'=>'Название'/*, 'type' => 'raw', 'value' => 'CHtml::link($data->name, Yii::app()->controller->createUrl("show", array("id" => $data->id)))'*/),
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
				//'htmlOptions'=>array('style'=>'width: 50px'),
			),
		),
	));
} else {
	echo 'Ни одного Юридического лица не зарегистрировано.';
}
?>

<a class="btn btn-success" href="<?=$this->createUrl('add')?>">Добавить Юр.Лицо</a>
