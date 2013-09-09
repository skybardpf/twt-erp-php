<?php
/**
 * Форма добавления/редактирования менеджера
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_person_leaderController $this
 * @var InterestedPersonManager $model
 * @var Organization $organization
 */

?>

<h2><?= ($model->primaryKey ? 'Редактирование' : 'Создание') . ' менеджера' ?></h2>

<?php
/** @var $form MTbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
    'id' => 'horizontalForm',
    'type' => 'horizontal',
));

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => 'Сохранить'
));

echo '&nbsp;';

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'label' => 'Отмена',
    'url' => $model->primaryKey
        ? $this->createUrl(
            'view',
            array(
                'id' => $model->primaryKey,
                'type_lico' => $model->type_lico,
                'org_id' => $model->id_yur,
                'org_type' => $model->type_yur,
                'date' => $model->date,
                'number_stake' => $model->number_stake,
            )
        )
        : $this->createUrl(
            'interested_person/index',
            array(
                'org_id' => $organization->primaryKey,
                'type' => $model->pageTypePerson
            )
        )
));

if ($model->hasErrors()) {
    echo '<br/><br/>';
    echo $form->errorSummary($model);
}

// Опции для JUI селектора даты
$jui_date_options = array(
    'language' => 'ru',
    'options' => array(
        'showAnim' => 'fold',
        'dateFormat' => 'yy-mm-dd',
        'changeMonth' => true,
        'changeYear' => true,
        'showOn' => 'button',
        'constrainInput' => 'true',
    ),
    'htmlOptions' => array(
        'style' => 'height:20px;'
    )
);
?>

<fieldset>
    <div class="control-group ">
        <div class="controls">
            <div class="add-individual">
                <?= CHtml::link('Создать новое физическое лицо', $this->createUrl('individual/add')); ?>
            </div>
        </div>
    </div>
    <div class="control-group list-individuals">
        <?= $form->labelEx($model, 'individual_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?= CHtml::activeDropDownList($model, 'individual_id', Individual::model()->listNames()); ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'date'
                ), $jui_date_options
            ));
            ?>
        </div>
    </div>

    <?php
    if (is_bool($model->deleted)) {
        $model->deleted = $model->deleted ? 1 : 0;
    } else {
        $model->deleted = 0;
    }
    echo $form->radioButtonListInlineRow($model, 'deleted', $model->getStatuses());
    echo $form->textFieldRow($model, 'job_title');
    echo $form->textAreaRow($model, 'description');
    ?>
</fieldset>

<?php $this->endWidget(); ?>
