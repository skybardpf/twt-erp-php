<?php
/**
 *  Банковские счета -> Список.
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this      My_organizationsController
 *  @var $accounts  SettlementAccount[]
 */
?>

<?php
    $this->beginContent('/my_organizations/show');
?>

<div class="pull-right" style="margin-top: 15px;">
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новый банковский счёт',
        'type'  => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'  => 'normal', // null, 'large', 'small' or 'mini'
        'url'   => $this->createUrl("settlement", array('action' => 'create', 'id' => $this->organization->primaryKey))
    ));
?>
</div>

<h3>Банковские счета</h3>
<?php
    $data = new CArrayDataProvider($accounts);

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'dataProvider' => $data,
        'template'=>"{items}",
        'columns'=>array(
            array(
                'name'  => 'id',
                'header'=> '#',
                'type'  => 'raw',
                'value' => 'CHtml::link($data["id"], Yii::app()->getController()->createUrl("settlement", array("action" => "show", "id" => $data["id"])))'
            ),
            array(
                'name'  => 'type_yur',
                'header'=> 'Тип юр. лица',
                'value' => '$data["type_yur"]'
            ),
            array(
                'name'  => 'name',
                'header'=>'Название'
            ),
        ),
    ));

    $this->endContent();
?>