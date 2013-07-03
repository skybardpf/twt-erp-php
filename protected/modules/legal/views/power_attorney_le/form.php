<?php
/**
 *  Документы -> Доверенности. Форма редактирования.
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 *
 *  @var $this          Power_attorney_leController
 *  @var $model         PowerAttorneysLE
 *  @var $organization  Organizations
 *  @var $form          TbActiveForm
 */
?>

<?php
    echo '<h2>'.($model->primaryKey ? 'Редактирование ' : 'Создание ').'доверенности</h2>';

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'    => 'model-form-form',
        'type'  => 'horizontal',
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
        'url'        => $model->primaryKey
            ? $this->createUrl('view', array('id' => $model->primaryKey))
            : $this->createUrl('documents/list', array('org_id' => $organization->primaryKey))
    ));

    if ($error) {
        echo '<br/><br/>';
        echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div');
    }
    $error = $form->errorSummary($model);
    if (empty($error)) {
//        echo '<br/><br/>';
        echo $error;
    }
?>

<fieldset>
<?php
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

    echo $form->dropDownListRow($model, 'id_lico', Individuals::getValues(), array('class' => 'span6'));
    echo $form->textFieldRow($model, 'nom', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'name', array('class' => 'span6'));
//    if (!$model->getprimaryKey()){
//        echo $form->dropDownListRow($model, 'type_yur', PowerAttorneysLE::getYurTypes(), array('class' => 'span6'));
//    }
    echo $form->dropDownListRow($model, 'typ_doc', PowerAttorneysLE::getDocTypes(), array('class' => 'span6'));
    //
    // Список видов договоров будет здесь.
    //
?>
<?php /** date */ ?>
<div class="control-group">
    <label class="control-label" for="PowerAttorneysLE_date">
        <?= $model->getAttributeLabel("date") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'date'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php /* expire */?>
<div class="control-group">
    <label class="control-label" for="PowerAttorneysLE_expire">
        <?= $model->getAttributeLabel("expire") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'expire'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php /* break */?>
<div class="control-group">
    <label class="control-label" for="PowerAttorneysLE_break">
        <?= $model->getAttributeLabel("break"); ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'break'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php
    echo $form->textAreaRow($model, 'comment', array('class' => 'span6'));
?>
</fieldset>

<?php $this->endWidget(); ?>