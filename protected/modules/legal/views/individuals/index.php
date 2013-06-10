<?php
/**
 * Список Физ.Лиц
 *
 * @var $this IndividualsController
/* @var $elements array 
 */
 ?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Новое физическое лицо',
        'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'=>'normal', // null, 'large', 'small' or 'mini'
        'url' => Yii::app()->getController()->createUrl("add")
    )); ?>
</div>
<h2>Мои физические лица</h2>
<?php
if ($elements) {
	$gridDataProvider = new CArrayDataProvider($elements);

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'=>'striped',
		'dataProvider' => $gridDataProvider,
		'columns'=>array(
			array('name'=>'id', 'header'=>'#'),
            array(
                    'name'=>'name', 
                    'header'=>'Название', 
                    'type' => 'raw', 
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("view", array("id" => $data["id"])))'
            ),
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
			),
		),
	));
} else {
	echo 'Ни одного Физического лица не зарегистрировано.';
}
?>
