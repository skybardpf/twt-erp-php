<?php
/**
 * @var $this Template_exampleController
 */
?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'link', 'type'=>'success', 'label'=>'Редактировать', 'url' => Yii::app()->getController()->createUrl("add"))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type' => 'danger', 'label'=>'Удалить')); ?>
    <br>
    <br>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data'=>array(
            'id'=>1,
            'country'=>'Россия',
            'shorttitle' => 'ООО "Рога и копыта"',
            'fulltitle' => 'ООО "Рога и копыта"',
            'comment' => 'Тилимилитрямдия',
            'inn' => '0140404045',
            'kpp' => '453204542',
            'ogrn' => '',
            'yuradress' => 'Москва, Красная пл. д.1',
            'fact_address' => 'Смоленск, ул. Нормандии-Неман д.23В',
            'reg_nom' => '',
            'sert_nom' => '',
            'profile' => '',
        ),
        'attributes'=>array(
            array('name'=>'country', 'label'=>'Страна'),
            array('name'=>'shorttitle', 'label'=>'Сокращённое наименование'),
            array('name'=>'fulltitle', 'label'=>'Полное наименование'),
            array('name'=>'comment', 'label'=>'Комментарий'),
            array('name'=>'inn', 'label'=>'ИНН'),
            array('name'=>'kpp', 'label'=>'КПП'),
            array('name'=>'ogrn', 'label'=>'ОГРН'),
            array('name'=>'yuradress', 'label'=>'Юридический адрес'),
            array('name'=>'fact_address', 'label'=>'Фактический адрес'),
            array('name'=>'reg_nom', 'label'=>'Регистрационный номер'),
            array('name'=>'sert_nom', 'label'=>'Номер сертификата'),
            array('name'=>'profile', 'label'=>'Вид деятельности'),
        ),
    )); ?>