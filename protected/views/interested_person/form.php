<?php
    /**
     * Форма добавления/редактирования заитересованного лица.
     *
     * @author Skibardin A.A. <webprofi1983@gmail.com>
     *
     * @var $this           Interested_personsController
     * @var $model          InterestedPerson
     * @var $organization   Organization
     * @var $form           TbActiveForm
     */

    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/interested_person/form.js');
?>

<h2><?=($model->primaryKey ? 'Редактирование' : 'Создание').' заинтересованного лица'?></h2>

<?php
    /** @var $form MTbActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id'    => 'horizontalForm',
        'type'  => 'horizontal',
    ));

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Сохранить'
    ));

    echo '&nbsp;';

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'label'      => 'Отмена',
        'url'        => $model->primaryKey
            ? $this->createUrl(
                'view',
                array(
                    'id' => $model->primaryKey,
                    'id_yur' => $model->id_yur,
                    'role' => $model->role,
                )
            )
            : $this->createUrl(
                'index',
                array(
                    'org_id' => $organization->primaryKey
                )
            )
    ));

    if ($model->hasErrors()) {
        echo '<br/><br/>';
        echo $form->errorSummary($model);
    }

    // Опции для JUI селектора даты
    $jui_date_options = array(
        'options'=>array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
        ),
        'htmlOptions'=>array(
            'style' => 'height:20px;'
        )
    );
?>

<fieldset>
    <?php
    /*
    ?>
    <div class="control-group links-create">
        <!--        --><?//= $form->labelEx($model, 'list_individuals', array('class' => 'control-label')); ?>
    <div class="controls">
        <?= CHtml::link('Создать новое физическое лицо', $this->createUrl('individuals/add')); ?>
            <?= '<br/>'; ?>
            <?= CHtml::link('Создать новое юридическое лицо', $this->createUrl('organization/add')); ?>
    </div>
    </div>
    */
    ?>

<?php
//    echo $form->radioButtonListInlineRow($model, 'type_lico', InterestedPerson::getPersonTypes());

//    if ($model->type_lico == InterestedPerson::TYPE_LICO_ORGANIZATION){
//        $class_org = '';
//        $class_person = 'hide';
//    } elseif ($model->type_lico == InterestedPerson::TYPE_LICO_INDIVIDUAL){
//        $class_org = 'hide';
//        $class_person = '';
//    } else {
//        $class_org = 'hide';
//        $class_person = 'hide';
//    }
?>
<!--    <div class="control-group list-individuals --><?//= $class_person; ?><!--">-->
<!--        --><?//= $form->labelEx($model, 'list_individuals', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--            --><?//= CHtml::activeDropDownList($model, 'list_individuals', Individuals::getValues()/*, array('class' => 'span6')*/); ?>
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="control-group list-organizations --><?//= $class_org; ?><!--">-->
<!--        --><?//= $form->labelEx($model, 'list_organizations', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--            --><?//= CHtml::activeDropDownList($model, 'list_organizations', Organizations::getValues()/*, array('class' => 'span6')*/); ?>
<!--        </div>-->
<!--    </div>-->

<?php
?>

<div class="control-group">
    <?= $form->labelEx($model, 'role', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo CHtml::textField('role', $model->role, array('disabled' => true)); ?>
    </div>
</div>

<div class="control-group">
    <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'date'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php
    if (is_bool($model->deleted)){
        $model->deleted = $model->deleted ? 0 : 1;
    } else {
        $model->deleted = 1;
    }

    echo $form->radioButtonListInlineRow($model, 'deleted', array(
       1 => 'Действителен',
       0 => 'Недействителен',
    ));

    $class_hide = ($model->role == InterestedPerson::ROLE_DIRECTOR) ? '' : 'hide';
?>

<div class="control-group job-title <?= $class_hide; ?>">
    <?= $form->labelEx($model, 'job_title', array('class' => 'control-label')); ?>
    <div class="controls">
        <?= CHtml::activeTextField($model, 'job_title', Organization::model()->getListNames()); ?>
    </div>
</div>

<?php
    $class_hide = (in_array($model->role, array(InterestedPerson::ROLE_SHAREHOLDER, InterestedPerson::ROLE_BENEFICIARY))) ? '' : 'hide';
?>

<div class="control-group percent <?= $class_hide; ?>">
    <?= $form->labelEx($model, 'percent', array('class' => 'control-label')); ?>
    <div class="controls">
        <?= CHtml::activeTextField($model, 'percent'); ?>
    </div>
</div>

<div class="control-group date-issue <?= $class_hide; ?>">
    <?= $form->labelEx($model, 'dateIssue', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'dateIssue',
                ), $jui_date_options
            ));
        ?>
    </div>
</div>

<div class="control-group num-pack <?= $class_hide; ?>">
    <?= $form->labelEx($model, 'numPack', array('class' => 'control-label')); ?>
    <div class="controls">
        <?= CHtml::activeTextField($model, 'numPack'); ?>
    </div>
</div>

<?php
    $checked_0 = '';
    $checked_1 = '';
    if ($model->typeStock == 'Обыкновенные'){
        $checked_0 = 'checked';
    } elseif ($model->typeStock == 'Привилегированные'){
        $checked_1 = 'checked';
    }

?>
<div class="control-group type-stock <?= $class_hide; ?>">
    <?= $form->labelEx($model, 'typeStock', array('class' => 'control-label')); ?>
    <div class="controls">
        <input id="ytInterestedPerson_typeStock" type="hidden" value="" name="InterestedPerson[typeStock]">
        <label class="radio inline">
            <input id="InterestedPerson_typeStock_0" value="Обыкновенные" type="radio" name="InterestedPerson[typeStock]" <?= $checked_0; ?>>
            <label for="InterestedPerson_typeStock_0">Обыкновенные</label>
        </label>
        <label class="radio inline">
            <input id="InterestedPerson_typeStock_1" value="Привилегированные" type="radio" name="InterestedPerson[typeStock]" <?= $checked_1; ?>>
            <label for="InterestedPerson_typeStock_1">Привилегированные</label>
        </label>
    </div>
</div>

<div class="control-group quant-stock <?= $class_hide; ?>">
    <?= $form->labelEx($model, 'quantStock', array('class' => 'control-label')); ?>
    <div class="controls">
        <?= CHtml::activeTextField($model, 'quantStock', array('type' => 'numerical')); ?>
    </div>
</div>

<?php
    echo $form->textAreaRow($model, 'add_info', array('class' => 'span6'));
?>
</fieldset>

<?php $this->endWidget(); ?>
