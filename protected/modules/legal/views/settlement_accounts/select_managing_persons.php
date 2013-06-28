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
?>

<!-- Здесь могут быть Ваши поля формы -->
<?php echo $form->errorSummary($model);?>

<?php //echo $form->dropDownListRow($model, 'name');?>
<?php echo CHtml::dropDownList('select_managing_person', '', $data);?>

    <!-- Скрытое поле, в котором лежит data_id -->
<?php //echo $form->textField($model,'data_id',array('class'=>'hide')); ?>

<?php $this->endWidget(); ?>