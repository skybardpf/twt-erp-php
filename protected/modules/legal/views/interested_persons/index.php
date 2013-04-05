<?php
/* @var $this Interested_personsController */
/* @var $elements array */

$this->breadcrumbs=array(
	$this->controller_title,
);
LegalEntities::getValues();
Individuals::getValues();
?>
<h2><?=$this->controller_title?></h2>
<?php
if ($elements) {
	$gridDataProvider = new CArrayDataProvider($elements);

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'=>'striped',
		'dataProvider' => $gridDataProvider,
		'columns'=>array(
			array('name' => 'id',       'header' => 'Лицо', /*'type' => 'raw', 'value' => '(strlen($data["id"]) == 11) ? LegalEntities::$values[$data["id"]] : Individuals::$values[$data["id"]]'*/),
			array('name' => 'role',     'header' => 'Роль'),
			array('name' => 'id_yur',   'header' => 'Юр.Лицо',  'type' => 'raw', 'value' => 'isset(LegalEntities::$values[$data["id_yur"]]) ? LegalEntities::$values[$data["id_yur"]] : "-"'),
			array(
				'class' => 'bootstrap.widgets.TbButtonColumn',
				'viewButtonUrl' => 'Yii::app()->getController()->createUrl("view", array("id" => $data->id, "id_yur" => $data->id_yur, "role" => $data->role))',
				'updateButtonUrl' => 'Yii::app()->getController()->createUrl("update", array("id" => $data->id, "id_yur" => $data->id_yur, "role" => $data->role))',
				'deleteButtonUrl' => 'Yii::app()->getController()->createUrl("delete", array("id" => $data->id, "id_yur" => $data->id_yur, "role" => $data->role))',
			),
		),
	));

} else {
	echo 'Ни одного заинтересованного лица не зарегистрировано.';
}
?>

<a class="btn btn-success" href="<?=$this->createUrl('add')?>">Добавить заинтересованное лицо</a>