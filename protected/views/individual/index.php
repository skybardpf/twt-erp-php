<?php
/**
 *  Список Физ.Лиц
 *
 *  @var IndividualController  $this
 *  @var bool                   $force_cache
 *  @var array                  $data
 */
 ?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новое физическое лицо',
        'type' => 'success',
        'size' => 'normal',
        'url' => $this->createUrl("add")
    )); ?>
</div>
<h2>Физические лица</h2>
<?php
    $countries = Country::model()->listNames($force_cache);

	$provider = new CArrayDataProvider($data);
    foreach($provider->rawData as $k=>$v){
        $provider->rawData[$k]['citizenship'] = isset($countries[$v["citizenship"]]) ? $countries[$v["citizenship"]] : ($v["citizenship"] ? $v["citizenship"] : "&mdash;");
//        $provider->rawData[$k]['phone'] = $v["email"].($v["email"] && $v["phone"] ? ",<br/>" : "").$v["phone"];
    }

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type' => 'striped bordered condensed',
		'dataProvider' => $provider,
        'template' => "{items} {pager}",
		'columns' => array(
            array(
                'name'   => 'name',
                'header' => 'ФИО',
                'type'   => 'raw',
                'value'  => 'CHtml::link($data["family"]." ".$data["name"]." ".$data["parent_name"], Yii::app()->getController()->createUrl("view", array("id" => $data["id"])))'
	        ),
			array(
                'name'   => 'citizenship',
				'header' => 'Гражданство',
				'type'   => 'raw'
			),
			array(
                'name' => 'ser_nom_pass',
                'header' => 'Серия и номер удостоверения'
            ),
			array(
				'header' => 'Контакты',
				'type'   => 'raw',
                'name'   => 'phone',
			),
		)
	));
?>
