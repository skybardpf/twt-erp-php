<?php
/**
 *  Банковские счета -> Модальная форма для добавления физ. лиц, управляющих счетом.
 *  User: Skibardin A.A.
 *  Date: 28.06.13
 *
 *  @var $this       Settlement_accountsController
 *  @var $data       array
 *  @var $form       TbActiveForm
 */
?>

<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'menu-form',
        'enableAjaxValidation'=>false,
        'type' => 'horizontal',
    ));
    echo CHtml::dropDownList('select_managing_person', '', $data);

    $this->endWidget();
?>