<?php
/**
 *  Список Физ.Лиц
 *
 *  @var $this      IndividualsController
 *  @var $elements  array
 */
 ?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Новое физическое лицо',
        'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'=>'normal', // null, 'large', 'small' or 'mini'
        'url' => $this->createUrl("add")
    )); ?>
</div>
<h2>Мои физические лица</h2>
<?php
// Инициализируем список стран
Countries::getValues();

if ($elements) {
	$gridDataProvider = new CArrayDataProvider($elements);

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'          => 'striped',
		'dataProvider'  => $gridDataProvider,
//        'template'      => "{pager}\n{items}\n{pager}",
		'columns'       => array(
			array('name' => 'id', 'header' => '#'),
            array(
                'name'   => 'name',
                'header' => 'ФИО',
                'type'   => 'raw',
                'value'  => 'CHtml::link($data["family"]." ".$data["name"]." ".$data["parent_name"], Yii::app()->getController()->createUrl("view", array("id" => $data["id"])))'
	        ),
			array(
				'header' => 'Гражданин',
				'type'   => 'raw',
				'value'  => 'isset(Countries::$values[$data["citizenship"]]) ? Countries::$values[$data["citizenship"]] : ($data["citizenship"] ? $data["citizenship"] : "&mdash;")'
			),
			array('name' => 'ser_nom_pass', 'header' => 'Серия и номер удостоверения'),
			array(
				'header' => 'Контакты',
				'type'   => 'raw',
				'value'  => '$data["email"].($data["email"] && $data["phone"] ? ",<br/>" : "").$data["phone"]'
			),
			array(
				'class' => 'bootstrap.widgets.TbButtonColumn',
				'updateButtonUrl' => 'Yii::app()->controller->createUrl("edit",array("id"=>$data["id"]))'
			),
		),
	));
} else {
	echo 'Ни одного Физического лица не зарегистрировано.';
}
?>
