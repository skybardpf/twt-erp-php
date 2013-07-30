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
    Yii::app()->clientScript->registerCssFile($this->asset_static.'/select2/select2.css');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/select2/select2.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/organization/form.js');

    echo '<h2>'.($model->primaryKey ? 'Редактирование ' : 'Создание ').'организации</h2>';

    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id'=>'form-organization',
        'type'=>'horizontal',
        'enableAjaxValidation' => true,
        'enableClientValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
        ),
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
<?php
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

    echo $form->dropDownListRow($model, 'country', Countries::getValues(), array('class' => 'list-countries'));
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
            echo $form->error($model, 'sert_date');
        ?>
        </div>
    </div>

    <!-- НАЧАЛО поля для российских фирм -->
    <div id="rus_fields">
    <?php
        echo $form->textFieldRow($model, 'inn');
        echo $form->textFieldRow($model, 'kpp');
        echo $form->textFieldRow($model, 'ogrn');
    ?>
    </div>
    <!-- КОНЕЦ поля для российских фирм -->

    <!-- НАЧАЛО поля для иностранных фирм -->
    <div id="foreign_fields">
    <?php
        echo $form->textFieldRow($model, 'vat_nom');
        echo $form->textFieldRow($model, 'reg_nom');
        echo $form->textFieldRow($model, 'sert_nom');
    ?>
    </div>
    <!-- КОНЕЦ поля для иностранных фирм -->

<?php
    echo $form->textAreaRow($model, 'info');
?>
    <div class="control-group">
        <?= $form->labelEx($model, 'profile', array('class' => 'control-label')); ?>
        <div class="controls">
            <input class="input-profile"
                id = '<?= get_class($model).'_profile'; ?>'
                type="text"
                name="<?= get_class($model).'[profile]'; ?>"
                data-placeholder="Виды деятельности"
                data-tnved="1"
                data-minimum_input_length="4"
                data-allow_clear="1"
                data-ajax="1"
                data-ajax_url="<?= $this->createUrl('get_activities_types'); ?>"
                value="<?= $model->profile; ?>">
            <?= $form->error($model, 'profile'); ?>
        </div>
    </div>
<?php
    echo $form->textFieldRow($model, 'yur_address');
    echo $form->textFieldRow($model, 'fact_address');
    echo $form->textFieldRow($model, 'email');
    echo $form->textFieldRow($model, 'phone');
    echo $form->textFieldRow($model, 'fax');
    echo $form->textAreaRow($model, 'comment');
?>
</fieldset>

<?php $this->endWidget(); ?>