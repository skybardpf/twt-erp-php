<?php
/** @var $this Template_exampleController */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Новое мероприятие',
        'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'=>'normal', // null, 'large', 'small' or 'mini'
        'url' => Yii::app()->getController()->createUrl("event_add")
    )); ?>
</div>
<h2>Мои мероприятия</h2>

<?php
$gridDataProvider = new CArrayDataProvider(array(
    array('id'=>1, 'title'=>'Сдача квартальной отчётности', 'first_date'=>'02.02.02', 'period'=>'ежеквартально', 'plannedfor'=>'ООО "Mark"'),
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$gridDataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'title', 'header'=>'Название', 'type' => 'raw', 'value' => 'CHtml::link($data["title"], Yii::app()->getController()->createUrl("event_show", array("id" => $data["id"])))'),
        array('name'=>'first_date', 'header'=>'Первая дата'),
        array('name'=>'period', 'header'=>'Периодичность'),
        array('name'=>'plannedfor', 'header'=>'Запланировано для юридических лиц', 'type' => 'raw', 'value' => 'CHtml::link($data["plannedfor"], Yii::app()->getController()->createUrl("show", array("id" => $data["id"])))'),
    ),
));
?>