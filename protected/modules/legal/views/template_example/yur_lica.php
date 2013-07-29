<?php
/**
 * @var $this Controller
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Новое юридическое лицо',
        'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'=>'normal', // null, 'large', 'small' or 'mini'
        'url' => Yii::app()->getController()->createUrl("add")
    )); ?>
</div>
<h2>Мои юридические лица</h2>

<?php
    $gridDataProvider = new CArrayDataProvider(array(
    array('id'=>1, 'title'=>'ООО "Mark"', 'country'=>'Россия', 'inn_kpp'=>'000151022 / 4245040'),
    array('id'=>2, 'title'=>'ООО "Рога и копыта"', 'country'=>'Россия', 'inn_kpp'=>'11100111 / 10001010'),
    array('id'=>3, 'title'=>'ООО "Заветы Ильича"', 'country'=>'Тилимилитрямдия', 'inn_kpp'=>'0245011 / 014124'),
    ));

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'dataProvider'=>$gridDataProvider,
        'template'=>"{items}",
        'columns'=>array(
            array('name'=>'id', 'header'=>'#'),
            array('name'=>'title', 'header'=>'Название', 'type' => 'raw', 'value' => 'CHtml::link($data["title"], Yii::app()->getController()->createUrl("show", array("id" => $data["id"])))'),
            array('name'=>'country', 'header'=>'Страна'),
            array('name'=>'inn_kpp', 'header'=>'ИНН/КПП')
        ),
    ));
?>