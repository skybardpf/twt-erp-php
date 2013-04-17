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
    array('id'=>1, 'title'=>'Сдача квартальной отчётности', 'first_date'=>'15.01.2009', 'period'=>'Ежеквартально', 'plannedfor'=>'ООО "Ромашка"<br/>ООО "Рога и копыта"'),
    array('id'=>2, 'title'=>'Сдача годовой отчётности', 'first_date'=>'15.01.2009', 'period'=>'Ежегодно', 'plannedfor'=>'ООО "Ромашка"<br/>ООО "Рога и копыта"'),
    array('id'=>3, 'title'=>'Собрание акционеров', 'first_date'=>'21.10.2010', 'period'=>'Ежегодно', 'plannedfor'=>'Horns and Hooves, Ltd.'),
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