<?php
/**
 *  Документы -> Доверенности. Форма редактирования доверенности.
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this       My_organizationsController
 *  @var $doc        PowerAttorneysLE
 *  @var $form       TbActiveForm
 */
?>

<?php
    $this->beginContent('/my_organizations/show');

    echo '<h2>'.($doc->primaryKey ? 'Редактирование ' : 'Создание ').'доверенности</h2>';

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
        'url'        => $doc->primaryKey
            ? $this->createUrl('power_attorney_le', array('action' => 'show', 'id' => $doc->primaryKey))
            : $this->createUrl('documents', array('id' => $this->organization->primaryKey))
    ));

    if ($error) {
        echo '<br/><br/>';
        echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div');
    }
    $error = $form->errorSummary($doc);
    if (empty($error)) {
//        echo '<br/><br/>';
        echo $error;
    }
?>

<?
    /*id:,
    name: rt300000002,
    date: 2013-11-25,
    from_user:true,
    user: Главбух,
    id_yur: 1000000005,
    nom:75,
    typ_doc: Генеральная,
    id_lico: 0000000001,
    loaded: 2013-11-25,
    expire: 2013-11-25,
    break: 2013-11-25,
    e_ver: rt34000000002,
    contract_types: [100432, 030432, 005432],
    scans: [ 00432, 00432, 00432]*/
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

    echo $form->dropDownListRow($doc, 'id_lico', Individuals::getValues(), array('class' => 'span6'));
    echo $form->textFieldRow($doc, 'nom', array('class' => 'span6'));
    echo $form->textFieldRow($doc, 'name', array('class' => 'span6'));
//    if (!$doc->getprimaryKey()){
//        echo $form->dropDownListRow($doc, 'type_yur', PowerAttorneysLE::getYurTypes(), array('class' => 'span6'));
//    }
    echo $form->dropDownListRow($doc, 'typ_doc', PowerAttorneysLE::getDocTypes(), array('class' => 'span6'));
    //
    // Список видов договоров будет здесь.
    //
?>
<?php /** date */ ?>
<div class="control-group">
    <label class="control-label" for="PowerAttorneysLE_date">
        <?= $doc->getAttributeLabel("date") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $doc,
                'attribute' => 'date'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php /* expire */?>
<div class="control-group">
    <label class="control-label" for="PowerAttorneysLE_expire">
        <?= $doc->getAttributeLabel("expire") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $doc,
                'attribute' => 'expire'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php /* break */?>
<div class="control-group">
    <label class="control-label" for="PowerAttorneysLE_break">
        <?= $doc->getAttributeLabel("break"); ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $doc,
                'attribute' => 'break'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php
    echo $form->textAreaRow($doc, 'comment', array('class' => 'span6'));
    //echo $form->textFieldRow($doc, 'loaded',      array('class'=>'span6'));
    //echo $form->textFieldRow($doc, 'e_ver',         array('class' => 'span6'));
?>
</fieldset>

<?php $this->endWidget(); ?>
<?php $this->endContent(); ?>