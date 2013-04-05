<?php
/* @var $this BeneficiaryController */
/* @var $elements array */

$this->breadcrumbs=array(
	$this->controller_title,
);
LegalEntities::getValues();
?>
<h2><?=$this->controller_title?></h2>
<?php
if ($elements) {
	$gridDataProvider = new CArrayDataProvider($elements);

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'=>'striped',
		'dataProvider' => $gridDataProvider,
		'columns'=>array(
			array('name'=>'id', 'header' => 'Лицо'),
			array('name' => 'id_yur',   'header' => 'Юр.Лицо',  'type' => 'raw', 'value' => 'isset(LegalEntities::$values[$data["id_yur"]]) ? LegalEntities::$values[$data["id_yur"]] : "-"'),
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
				'viewButtonUrl' => 'Yii::app()->getController()->createUrl("view", array("id" => $data->id, "id_yur" => $data->id_yur))',
				'updateButtonUrl' => 'Yii::app()->getController()->createUrl("update", array("id" => $data->id, "id_yur" => $data->id_yur))',
				'deleteButtonUrl' => 'Yii::app()->getController()->createUrl("delete", array("id" => $data->id, "id_yur" => $data->id_yur))',
			),
		),
	));
} else {
	echo 'Ни одного Бенефициара не зарегистрировано.';
}
?>

<a class="btn btn-success" href="<?=$this->createUrl('add')?>">Добавить Бенефициара</a>
