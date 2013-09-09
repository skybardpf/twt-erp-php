<?php
/**
 * Форма добавления/редактирования секретаря.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_person_shareholderController $this
 * @var InterestedPersonSecretary $model
 * @var Organization $organization
 */

Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/interested_person/form.js');
?>

<h2><?= ($model->primaryKey ? 'Редактирование' : 'Создание') . ' секретаря' ?></h2>

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
                'id_yur' => $model->id_yur,
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
    <?php
    echo $form->radioButtonListInlineRow($model, 'type_lico', $model->listPersonTypes(), array('class' => 'type_lico'));
    if ($model->type_lico == MTypeInterestedPerson::ORGANIZATION) {
        $class_org = '';
        $class_person = 'hide';
        $class_cont = 'hide';
    } elseif ($model->type_lico == MTypeInterestedPerson::INDIVIDUAL) {
        $class_org = 'hide';
        $class_person = '';
        $class_cont = 'hide';
    } elseif ($model->type_lico == MTypeInterestedPerson::CONTRACTOR) {
        $class_org = 'hide';
        $class_person = 'hide';
        $class_cont = '';
    } else {
        $class_org = 'hide';
        $class_person = 'hide';
        $class_cont = 'hide';
    }
    ?>
    <div class="control-group list-individuals <?= $class_person; ?>">
        <?= $form->labelEx($model, 'individual_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?= CHtml::activeDropDownList($model, 'individual_id', Individual::model()->listNames()); ?>
        </div>
    </div>
    <div class="control-group list-organizations <?= $class_org; ?>">
        <?= $form->labelEx($model, 'organization_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?= CHtml::activeDropDownList($model, 'organization_id', Organization::model()->getListNames()); ?>
        </div>
    </div>
    <div class="control-group list-contractors <?= $class_cont; ?>">
        <?= $form->labelEx($model, 'contractor_id', array('class' => 'control-label')); ?>
        <div class="controls">
            <?= CHtml::activeDropDownList($model, 'contractor_id', Contractor::model()->getListNames()); ?>
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
