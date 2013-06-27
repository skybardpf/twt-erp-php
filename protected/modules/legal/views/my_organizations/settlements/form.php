<?php
/**
 *  Банковские счета -> Форма редактирования банковского счета.
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this       My_OrganizationsController
 *  @var $model      SettlementAccount
 *  @var $form       TbActiveForm
 */
?>

<?php
    $this->beginContent('/my_organizations/show');

    echo '<h2>'.($model->primaryKey ? 'Редактирование ' : 'Создание ').'банковского счета</h2>';

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'    => 'horizontalForm',
        'type'  => 'horizontal',
    ));

    // Опции для JUI селектора даты
    $jui_date_options = array(
        'options'=>array(
            'showAnim'=>'fold',
            'dateFormat' => 'yy-mm-dd',
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;'
        )
    );

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
            ? $this->createUrl('settlement', array('action' => 'show', 'id' => $model->primaryKey))
            : $this->createUrl('settlements', array('id' => $this->organization->primaryKey))
    ));

    if ($error) {
        echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div');
    }
    echo $form->errorSummary($model);
?>

<fieldset>
<?php
    $vid = array(
        'Расчетный' => 'Расчетный',
        'Депзитный' => 'Депзитный',
        'Ссудный'   => 'Ссудный',
        'Аккредитивный' => 'Аккредитивный',
        'Иной'      => 'Иной',
    );
    $service = array(
        'Самостоятельно' => 'Самостоятельно',
        'По доверению подписанту' => 'По доверению подписанту',
        'Обслуживаниеу нас' => 'Обслуживаниеу нас'
    );
    $management_method = array(
        'Все вместе' => 'Все вместе',
        'По одному' => 'По одному',
    );

    echo $form->textFieldRow($model, 's_nom', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'iban', array('class' => 'span6'));
    echo $form->dropDownListRow($model, 'cur', Currencies::getValues());

    echo $form->textFieldRow($model, 'bank', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'bank_name', array('class' => 'span6', 'readonly' => true));

    echo $form->dropDownListRow($model, 'vid', $vid);
    echo $form->dropDownListRow($model, 'service', $service);
    echo $form->textFieldRow($model, 'name', array('class' => 'span6'));
?>
<?php /** data_open */ ?>
<div class="control-group">
    <label class="control-label" for="SettlementAccount_data_open">
        <?= $model->getAttributeLabel("data_open") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'data_open'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php /** data_closed */ ?>
<div class="control-group">
    <label class="control-label" for="SettlementAccount_data_closed">
        <?= $model->getAttributeLabel("data_closed") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'data_closed'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php
    echo $form->textAreaRow($model, 'address', array('class' => 'span6'));
    echo $form->textAreaRow($model, 'contact', array('class' => 'span6'));
?>

<?php /** managing_persons */ ?>
<div class="control-group">
    <label class="control-label" for="SettlementAccount_managing_persons">
        <?= $model->getAttributeLabel("managing_persons") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        Здесь будет список
    </div>
</div>

<?php
    echo $form->radioButtonListInlineRow($model, 'management_method', $management_method);
?>
</fieldset>

<?php
    $this->endWidget();
    $this->endContent();
?>