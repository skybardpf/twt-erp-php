<?php
/**
 * Форма редактирования данных о контрагенте.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var ContractorController    $this
 * @var Contractor              $model
 */
?>

<?php
    Yii::app()->clientScript->registerCssFile($this->asset_static.'/select2/select2.css');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/select2/select2.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/contractor/form.js');

    echo '<h2>'.($model->primaryKey ? 'Редактирование' : 'Создание').' контрагента</h2>';

    /* @var $form MTbActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id' => 'form-my-events',
        'type' => 'horizontal',
        'enableAjaxValidation' => false,
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
        'url' => $model->primaryKey
            ? $this->createUrl('view', array('id' => $model->primaryKey))
            : $this->createUrl('index')
    ));

    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
    }
?>

<fieldset>
<?php
    // Опции для JUI селектора даты
    $jui_date_options = array(
        'language' => 'ru',
        'options'=>array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
            'showOn' => 'button',
            'constrainInput' => 'true',
        ),
        'htmlOptions'=>array(
            'style' => 'height:20px;'
        )
    );

    echo $form->dropDownListRow($model, 'country', Countries::getValues());
    echo $form->dropDownListRow($model, 'okopf', CodesOKOPF::getValues());
    echo $form->textFieldRow($model, 'name');
    echo $form->textFieldRow($model, 'full_name');
?>

<div class="control-group">
    <?= $form->labelEx($model, 'sert_date', array('class' => 'control-label')); ?>
    <div class="controls">
    <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'sert_date'
            ), $jui_date_options
        ));
    ?>
    </div>
</div>

<?php
    echo $form->textFieldRow($model, 'inn');
    echo $form->textFieldRow($model, 'kpp');
    echo $form->textAreaRow($model, 'info');
?>

<div class="control-group">
    <?= $form->labelEx($model, 'profile', array('class' => 'control-label')); ?>
    <div class="controls">
        <input
            type=""
            name="<?= get_class($model).'[profile]'; ?>"
            data-placeholder="Виды деятельности"
            data-tnved="1"
            data-minimum_input_length="4"
            data-allow_clear="1"
            data-ajax="1"
            data-ajax_url="<?= $this->createUrl('get_activities_types'); ?>"
            value="<?= $model->profile; ?>">
    </div>
</div>

<?php
    echo $form->textFieldRow($model, 'yur_address');
    echo $form->textFieldRow($model, 'fact_address');

    echo $form->dropDownListRow($model, 'gendirector', ContactPersonForContractors::getValues());

    echo $form->textFieldRow($model, 'email');
    echo $form->textFieldRow($model, 'phone');
    echo $form->textFieldRow($model, 'fax');
    echo $form->textAreaRow($model, 'comment');
?>

</fieldset>

<?php $this->endWidget(); ?>