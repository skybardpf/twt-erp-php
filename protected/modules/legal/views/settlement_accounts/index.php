<?php
/* @var $this Settlement_accountController */
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
			array('name' => 'id',       'header' => '#'),
			array('name' => 'name',     'header' => 'Название', /*'type' => 'raw', 'value' => 'isset(Individuals::$values[$data["manager"]]) ? Individuals::$values[$data["manager"]] : "-"'*/),
			//array('name' => 'id_yur', 'header' => 'Юр.Лицо',
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
			),/*'id'                => '#',                                 // +
			'managing_rights'   => 'Вид прав на управление',            // +
			'id_acc'            => 'Расчетный счет',
			'manager'           => 'Физическое лицо',
			'user'              => '',
			'deleted'           => 'На удаление'*/
		),
	));
} else {
	echo 'Ни одного расчетного счета не зарегистрировано.';
}
?>

<a class="btn btn-success" href="<?=$this->createUrl('add')?>">Добавить расчетный счет</a>