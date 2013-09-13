<?php
/**
 * Список видов договоров
 * @var Contract_typeController $this
 * @var array $data
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новый вид договора',
        'type' => 'success',
        'size' => 'normal',
        'url' => $this->createUrl("add")
    )); ?>
</div>
<h2>Виды договоров</h2>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => new CArrayDataProvider($data),
    'template' => "{items} {pager}",
    'columns' => array(
        array(
            'name' => 'id',
            'header' => 'Номер',
        ),
        array(
            'name' => 'name',
            'header' => 'Название',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data["name"]), Yii::app()->getController()->createUrl("view", array("id" => $data["id"])))'
        ),
    )
));