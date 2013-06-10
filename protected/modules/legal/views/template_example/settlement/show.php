<?php
/** @var $this Template_exampleController */
?>

<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'link',
        'type'=>'success',
        'label'=>'Редактировать',
        'url' => Yii::app()->getController()->createUrl("settlement_add", array('id'=>$id)))
    ); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type' => 'danger', 'label'=>'Удалить')); ?>
</div>
<h2>Лицевой счёт</h2>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>array(
        's_nom'         => '73734743747456',
        'bank'          => 'Самый Лучший Банк',
        'vid'           => 'Какой-то вид',
        'name'          => 'Счётъ',
        'iban'          => '',
        'data_open'     => '21.32.43',
        'data_closed'   => '11.21.45',
        'corrbank'      => 'Сбербанка',
        'corr_account'  => '46457645635634',
        'cur'           => 'UAH',
        'recomend'      => 'Винни Пух',
        'contact'       => '',
        'person'        => '',
    ),
    'attributes'=>array(
        array('name'=>'s_nom', 'label'=>'Номер счета'),
        array('name'=>'bank', 'label'=>'Банк'),
        array('name'=>'vid', 'label'=>'Вид счета'),
        array('name'=>'name', 'label'=>'Название'),
        array('name'=>'iban', 'label'=>'IBAN'),
        array('name'=>'data_open', 'label'=>'Дата открытия'),
        array('name'=>'data_closed', 'label'=>'Дата закрытия'),
        array('name'=>'corrbank', 'label'=>'Банк-корреспондент'),
        array('name'=>'corr_account', 'label'=>'Счет банка-корреспондента'),
        array('name'=>'cur', 'label'=>'Валюта'),
        array('name'=>'recomend', 'label'=>'Рекомендатель'),
        array('name'=>'contact', 'label'=>'Контакты в отделении'),
        array('name'=>'person', 'label'=>'Управляющие персоны', 'type'=>'raw', 'value'=>'<a href="'.Yii::app()->getController()->createUrl("person_show", array("id" => 5)).'">Малхасян Геворк Рубенович</a><br><a href="'.Yii::app()->getController()->createUrl("person_show", array("id" => 1)).'">Померанцев Павел Вячеславович</a>'),
    ),
)); ?>
