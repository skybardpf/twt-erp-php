<?php
/** @var $this Template_exampleController */
?>

<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'link',
        'type'=>'success',
        'label'=>'Редактировать',
        'url' => Yii::app()->getController()->createUrl("benefit_add", array('id'=>$id)))
    ); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type' => 'danger', 'label'=>'Удалить')); ?>
</div>
<h2>Бенефициар</h2>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>array(
        'id'=>'Какое-то лицо тут',
        'vid'=>'Физическое',
        'role' => '',
        'percent' => '20%',
        'cost' => '12 898 478 374',
        'add_info' => '-',
    ),
    'attributes'=>array(
        array('name'=>'id', 'label'=>'Физическое или юридическое лицо'),
        array('name'=>'role', 'label'=>'Роль'),
        array('name'=>'percent', 'label'=>'Величина пакета акций'),
        array('name'=>'cost', 'label'=>'Номинальная стоимость пакета акций'),
        array('name'=>'add_info', 'label'=>'Дополнительные сведения'),
    ),
)); ?>