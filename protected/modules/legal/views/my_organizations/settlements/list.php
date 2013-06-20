<?php
/**
 * @var $this My_organizationsController
 */

$this->beginContent('/my_organizations/show'); ?>

<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Новый расчётный счёт',
        'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'=>'normal', // null, 'large', 'small' or 'mini'
        'url' => Yii::app()->getController()->createUrl("add_settlement", array('id' => $this->organization->primaryKey))
    )); ?>
</div>

<h3>Расчётные счета</h3>
<?php
$gridDataProvider = new CArrayDataProvider(array(
    array('id'=>1, 'num'=>'12412342434433242', 'owner'=>'Окуджава Булат Шалвович', 'currency'=>'RUR', 'bank'=>'Сбербанк'),
    array('id'=>2, 'num'=>'12342341234312234', 'owner'=>'Окуджава Булат Шалвович', 'currency'=>'EUR', 'bank'=>'Альфа банк'),
    array('id'=>3, 'num'=>'31243124123341234', 'owner'=>'Навальный Алексей Анатольевич', 'currency'=>'USD', 'bank'=>'Райффайзен'),
    array('id'=>3, 'num'=>'23455454357436875', 'owner'=>'Джигурда Никита Борисович', 'currency'=>'RUR', 'bank'=>'Русский Стандарт'),
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$gridDataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array(
            'name'=>'num',
            'header'=>'Номер',
            'type' => 'raw',
            'value' => 'CHtml::link($data["num"], Yii::app()->getController()->createUrl("settlement_show", array("id" => $data["id"])))'
        ),
        array('name'=>'currency', 'header'=>'Валюта'),
        array('name'=>'bank', 'header'=>'Банк'),
        array(
            'name'=>'owner',
            'header'=>'Управляющая персона',
            'type' => 'raw',
            'value' => 'CHtml::link($data["owner"], Yii::app()->getController()->createUrl("person_show", array("id" => $data["id"])))'
        ),
    ),
));

$this->endContent();
?>