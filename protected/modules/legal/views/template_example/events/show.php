<?php
/** @var $this Template_exampleController */
?>

<h2>Сдача квартальной отчётности</h2>
<div style="margin: 15px 0;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'link',
            'type'=>'success',
            'label'=>'Редактировать',
            'url' => Yii::app()->getController()->createUrl("even_add", array('id'=>1)))
    ); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type' => 'danger', 'label'=>'Удалить')); ?>
</div>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>array(
        'id'=>1,
        'title'=>'Сдача квартальной отчётности',
        'for' => 'ООО "Ромашка"',
    ),
    'attributes'=>array(
        array('name'=>'title', 'label'=>'Название'),
        array('name'=>'for', 'label'=>'Для юридического лица'),
    ),
)); ?>
