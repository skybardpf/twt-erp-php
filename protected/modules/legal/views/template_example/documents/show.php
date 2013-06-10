<?php
/** @var $this Template_exampleController */
?>

<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'link',
        'type'=>'success',
        'label'=>'Редактировать',
        'url' => Yii::app()->getController()->createUrl("document_add", array('id'=>$id)))
    ); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type' => 'danger', 'label'=>'Удалить')); ?>
</div>
<h2>Доверенность</h2>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>array(
        'id'=>1,
        'owner'=>'Джигурда Никита Борисович',
        'title' => 'доверенность 1',
        'type' => 'raw',
        'vid' => 'некий вид',
        'types' => CHtml::link("Договор купли-продажи", Yii::app()->getController()->createUrl("document_show", array("id" => $id))),
        'term' => '31.23.32',
    ),
    'attributes'=>array(
        array('name'=>'owner', 'label'=>'На кого оформлена'),
        array('name'=>'id', 'label'=>'Номер'),
        array('name'=>'title', 'label'=>'Наименование'),
        array('name'=>'vid', 'label'=>'Вид'),
        array(
            'name'=>'types',
            'label'=>'Виды договоров',
            'type'=>'raw',
            'value'=>CHtml::link("Договор купли-продажи", Yii::app()->getController()->createUrl("document_show", array("id" => $id))),
        ),
        array('name'=>'term', 'label'=>'Срок действия'),
    ),
)); ?>
<a href="#">Скачать электронную версию</a>
<br>
<br>
<a href="#">Скачать скан</a>
