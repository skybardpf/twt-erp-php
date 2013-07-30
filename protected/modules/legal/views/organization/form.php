<?php
/**
 *  Добавление новой организации и редактирование старой
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 *  @var OrganizationController $this
 *  @var Organization           $model
 *  @var MTbActiveForm          $form
 */
?>

<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/organization/one.js', CClientScript::POS_HEAD);

    echo '<h2>'.($model->primaryKey ? 'Редактирование ' : 'Создание ').'юридического лица</h2>';

    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id'=>'addOrganization',
        'type'=>'horizontal',
    ));

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'submit',
        'type'      => 'primary',
        'label'     => 'Сохранить'
    ));
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'label'      => 'Отмена',
        'url'        => $model->primaryKey
            ? $this->createUrl('view', array('id' => $model->primaryKey))
            : $this->createUrl('index')
    ));
?>

<?php
    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
    }
?>

<fieldset>
    <?php echo $form->dropDownListRow($model, 'country', Countries::getValues()); ?>
	<?php echo $form->dropDownListRow($model, 'okopf', CodesOKOPF::getValues()); ?>
    <?php echo $form->textFieldRow($model, 'name'); ?>
    <?php echo $form->textFieldRow($model, 'full_name'); // на удаление ?>

    <div class="control-group">
        <?= $form->labelEx($model, 'sert_date', array('class' => 'control-label')); ?>
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

    <?php echo $form->textAreaRow($model, 'info'); ?>
    <?php echo $form->textFieldRow($model, 'profile'); // основной вид деятельности ?>
    <?php echo $form->textFieldRow($model, 'yur_address'); ?>
    <?php echo $form->textFieldRow($model, 'fact_address'); ?>
    <?php echo $form->textFieldRow($model, 'email'); ?>
    <?php echo $form->textFieldRow($model, 'phone'); ?>
    <?php echo $form->textFieldRow($model, 'fax'); ?>
    <?php echo $form->textAreaRow($model, 'comment'); ?>

    <!-- старые поля, но все еще используются, в теории потом их надо будет удалить -->
    <?php //echo $form->textFieldRow($model, 'eng_name'); // на удаление ?>
    <?php //echo $form->checkboxRow($model, 'resident'); // на удаление ?>
    <?php //echo $form->textFieldRow($model, 'type_no_res'); // на удаление ?>
    <?php //echo $form->checkboxRow($model, 'deleted'); // на удаление ?>
</fieldset>

<?php $this->endWidget(); ?>