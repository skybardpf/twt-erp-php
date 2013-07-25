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
    $countries = Countries::getValues();

	$data = new CArrayDataProvider($elements);
    foreach($data->rawData as $k=>$v){
        $data->rawData[$k]['citizenship'] = isset($countries[$v["citizenship"]]) ? $countries[$v["citizenship"]] : ($v["citizenship"] ? $v["citizenship"] : "&mdash;");
    }

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'          => 'striped',
		'dataProvider'  => $data,
        'template'      => "{items} {pager}",
		'columns'       => array(
			array('name' => 'id', 'header' => '#'),
            array(
                'name'   => 'name',
                'header' => 'ФИО',
                'type'   => 'raw',
                'value'  => 'CHtml::link($data["family"]." ".$data["name"]." ".$data["parent_name"], Yii::app()->getController()->createUrl("view", array("id" => $data["id"])))'
	        ),
			array(
                'name'   => 'citizenship',
				'header' => 'Гражданин',
				'type'   => 'raw'
			),
			array('name' => 'ser_nom_pass', 'header' => 'Серия и номер удостоверения'),
			array(
				'header' => 'Контакты',
				'type'   => 'raw',
				'value'  => '$data["email"].($data["email"] && $data["phone"] ? ",<br/>" : "").$data["phone"]'
			),
		),
	));
?>
