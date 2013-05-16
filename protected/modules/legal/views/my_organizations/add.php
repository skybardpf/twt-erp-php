<?php
/**
 * Добавление новой организации и редактирование старой
 */
/** @var Organizations */
//$model = new Organizations();
?>

<h1>Редактирование юридического лица</h1>

<?php /** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'addOrganization',
    'action'=> $this->createUrl('/legal/my_organizations/add', $url_params),
    'type'=>'horizontal',
)); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Сохранить')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Отмена')); ?>

<fieldset>

    <?php echo $form->dropDownListRow($model, 'country', $countries); ?>
    <?php //echo $form->dropDownListRow($model, 'opf'); // организационно правовая форма ?>
    <?php echo $form->textFieldRow($model, 'name'); ?>
    <div class="control-group">
        <label class="control-label" for="Organizations_sert_date"><?php echo $model->getAttributeLabel("sert_date"); ?></label>
        <div class="controls">
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                    'model' => $model,
                    'attribute' => 'sert_date',
                    // additional javascript options for the date picker plugin
                    'options'=>array(
                        'showAnim'=>'fold', 
                        'dateFormat' => 'yy-mm-dd'
                    ),
                    'htmlOptions' => array(
                        'class' => 'some_class',
                        'style'=>'height:20px;'
                    ),
                )); ?>
        </div>
    </div>
    <!-- НАЧАЛО поля для российских фирм -->
    <div id="rus_fields">
    <?php echo $form->textFieldRow($model, 'inn'); ?>
    <?php echo $form->textFieldRow($model, 'kpp'); ?>
    <?php if(false): // проверка на то, является ли пользователь контрагентом, если да, то поле не выводится ?>
        <?php echo $form->textFieldRow($model, 'ogrn'); ?>
    <?php endif ?>
    </div>
    <!-- КОНЕЦ поля для российских фирм -->
    <!-- НАЧАЛО поля для иностранных фирм -->
    <div id="foreign_fields">
    <?php echo $form->textFieldRow($model, 'vat_nom'); ?>
    <?php echo $form->textFieldRow($model, 'reg_nom'); ?>
    <?php echo $form->textFieldRow($model, 'sert_nom'); ?>
    </div>
    <!-- КОНЕЦ поля для иностранных фирм -->
    <?php echo $form->textFieldRow($model, 'profile'); // основной вид деятельности ?> 
    <?php echo $form->textFieldRow($model, 'yur_address'); ?>
    <?php echo $form->textFieldRow($model, 'fact_address'); ?>
    <?php //echo $form->textFieldRow($model, 'email'); ?>
    <?php //echo $form->textFieldRow($model, 'phone'); ?>
    <?php //echo $form->textFieldRow($model, 'fax'); ?>
    <?php //echo $form->textAreaRow($model, 'comment'); ?>
    
    <!-- старые поля, но все еще используются, в теории потом их надо будет удалить -->
    <?php echo $form->textFieldRow($model, 'full_name'); // на удаление ?>
    <?php echo $form->textFieldRow($model, 'eng_name'); // на удаление ?>
    <?php echo $form->checkboxRow($model, 'resident'); // на удаление ?>
    <?php echo $form->textFieldRow($model, 'type_no_res'); // на удаление ?>
    <?php echo $form->checkboxRow($model, 'deleted'); // на удаление ?>

</fieldset>

<?php $this->endWidget(); ?>