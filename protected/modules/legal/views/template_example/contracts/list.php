<?php
/**
 * @var $this Controller
 */
?>
    <div class="pull-right" style="margin-top: 15px;">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Новый договор',
            'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'size'=>'normal', // null, 'large', 'small' or 'mini'
            'url' => Yii::app()->getController()->createUrl("contract_add")
        )); ?>
    </div>
    <h2>Договоры</h2>

<?php
$gridDataProvider = new CArrayDataProvider(array(
    array('id'=>1, 'num'=>'101', 'title'=>'Договор', 'character'=>'Агентский договор', 'contragent'=>'ООО "Рога и копыта"', 'date'=>'20.03.2012'),
    array('id'=>2, 'num'=>'195', 'title'=>'Договор', 'character'=>'Договор аренды', 'contragent'=>'ООО "Василёк"', 'date'=>'18.05.2012'),
    array('id'=>3, 'num'=>'215', 'title'=>'Договор', 'character'=>'Договор купли-продажи', 'contragent'=>'ООО "Незабудка"', 'date'=>'03.10.2012'),
    array('id'=>4, 'num'=>'256', 'title'=>'Договор', 'character'=>'Агентский договор', 'contragent'=>'Horns and Hooves, Ltd.', 'date'=>'25.03.2013')
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$gridDataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'num', 'header'=>'Номер'),
        array('name'=>'title', 'header'=>'Название', 'type' => 'raw', 'value' => 'CHtml::link($data["title"], Yii::app()->getController()->createUrl("contract_show", array("id" => $data["id"])))'),
        array('name'=>'character', 'header'=>'Характер договора'),
        array('name'=>'contragent', 'header'=>'Контрагент', 'type' => 'raw', 'value' => 'CHtml::link($data["contragent"], Yii::app()->getController()->createUrl("show", array("id" => $data["id"])))'),
        array('name'=>'date', 'header'=>'Дата')
    ),
));
?>