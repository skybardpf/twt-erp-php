<?php
/**
 * @var $this Controller
 */

$model = new LegalEntities();
?>

<h1>Редактирование юридического лица</h1>

<?php /** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Сохранить')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Отмена')); ?>

<fieldset>

    <?php echo $form->dropDownListRow($model, 'country'); ?>
    <?php echo $form->textFieldRow($model, 'name'); ?>
    <?php echo $form->textFieldRow($model, 'full_name'); ?>
    <?php echo $form->textAreaRow($model, 'comment'); ?>
    <?php echo $form->textFieldRow($model, 'inn'); ?>
    <?php echo $form->textFieldRow($model, 'kpp'); ?>
    <?php echo $form->textFieldRow($model, 'ogrn'); ?>
    <?php echo $form->textFieldRow($model, 'yur_address'); ?>
    <?php echo $form->textFieldRow($model, 'fact_address'); ?>
    <?php echo $form->textFieldRow($model, 'reg_nom'); ?>
    <?php echo $form->textFieldRow($model, 'sert_nom'); ?>
    <?php echo $form->textFieldRow($model, 'profile'); ?>

</fieldset>

<?php $this->endWidget(); ?>