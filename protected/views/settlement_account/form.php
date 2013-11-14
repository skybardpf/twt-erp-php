<?php
/**
 * Банковские счета -> Форма редактирования банковского счета.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Settlement_accountController $this
 * @var SettlementAccount $model
 * @var Organization $organization
 */

Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/jquery.json-2.4.min.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/settlement_account/form.js');

echo '<h2>' . ($model->primaryKey ? 'Редактирование ' : 'Создание ') . 'банковского счета</h2>';

/**
 * @var MTbActiveForm $form
 */
$form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
    'id' => 'form-account',
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnChange' => true,
    ),
));

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
        ? $this->createUrl('view', array('id' => $model->primaryKey))
        : $this->createUrl('list', array('org_id' => $organization->primaryKey))
));

if ($model->hasErrors()) {
    echo '<br/><br/>' . $form->errorSummary($model);
}
?>

<fieldset>
    <?php
    echo $form->textFieldRow($model, 's_nom', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'iban', array('class' => 'span6'));
    echo $form->dropDownListRow($model, 'currency', Currency::model()->listNames($model->forceCached));

    echo $form->textFieldRow($model, 'bank', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'bank_name', array('class' => 'span6', 'readonly' => true));

    echo $form->dropDownListRow($model, 'type_account', SettlementAccount::getAccountTypes());
    echo $form->dropDownListRow($model, 'type_service', SettlementAccount::getServiceTypes());

    $type_view = $model->getTypeView();
    $type_view[SettlementAccount::TYPE_VIEW_NOT_SELECTED] = '--- Шаблон не выбран ---';

    $class = array('class' => 'span6');

    if (empty($model->s_nom) || empty($model->type_account) || empty($model->bank_name)){
        $class['disabled'] = true;
        $model->name = SettlementAccount::TYPE_VIEW_NOT_SELECTED;
    } elseif (!isset($type_view[$model->name])){
        if ($key = array_search($model->name, $type_view)) {
            $model->name = $key;
        } else {
            $model->name = SettlementAccount::TYPE_VIEW_NOT_SELECTED;
        }
    }
    echo CHtml::tag(
        'div',
        array('class' => 'block-type-view'),
        $form->dropDownListRow($model, 'name', $type_view, $class)
    );
    ?>
    <?php /** data_open */ ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'data_open', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'data_open'
                ), $jui_date_options
            )); ?>
        </div>
    </div>

    <?php /** data_closed */ ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'data_closed', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'data_closed'
                ), $jui_date_options
            )); ?>
        </div>
    </div>

    <?php
    echo $form->textFieldRow($model, 'correspondent_bank', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'correspondent_bank_name', array('class' => 'span6', 'readonly' => true));

    echo $form->textAreaRow($model, 'address', array('class' => 'span6'));
    echo $form->textAreaRow($model, 'contact', array('class' => 'span6'));
    ?>

    <?php
    /**
     * Заполняем блок, управляющих персон.
     */
    $data = array();
    if (!empty($model->managing_persons)){
        $p = Individual::model()->listNames($model->forceCached);
        foreach($model->managing_persons as $v){
            $data[] = array(
                'id' => $v,
                'name' => isset($p[$v])
                    ? CHtml::link($p[$v], $this->createUrl('individual/view', array('id' => $v)))
                    : '---',
                'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'button',
                    'type' => 'primary',
                    'label' => 'Удалить',
                    'htmlOptions' => array(
                        'class' => 'del-element',
                        'data-id' => $v,
                    )
                ), true)
            );
        }
    }
    $div_persons = $this->widget('bootstrap.widgets.TbGridView',
        array(
            'id' => 'grid-persons',
            'type' => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider($data),
            'template' => "{items}",
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => 'ФИО',
                    'type' => 'raw',
                    'htmlOptions' => array(
                        'style' => 'width: 90%',
                    )
                ),
                array(
                    'name' => 'delete',
                    'header' => '',
                    'type' => 'raw'
                ),
            )
        ),
        true
    );
    $div_persons .= $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'button',
        'type' => 'primary',
        'label' => 'Добавить',
        'htmlOptions' => array(
            'class' => 'add-person',
        )
    ), true);

    echo $form->hiddenField($model, 'json_managing_persons');
    ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'managing_persons'); ?>
        <div class="controls">
            <?= $div_persons; ?>
            <?= $form->error($model, 'managing_persons'); ?>
        </div>
    </div>

<?php
    echo $form->radioButtonListInlineRow($model, 'management_method', SettlementAccount::getManagementMethods());
?>
</fieldset>

<?php $this->endWidget(); ?>

<?php
// Модальное окошко для выбора физ. лица
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'dataModal'));
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4><?= Yii::t("menu", "Выберите управляющего счетом") ?></h4>
</div>
<div class="modal-body"></div>
<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t("menu", "Сохранить"),
        'url' => '#',
        'htmlOptions' => array('class' => 'button_save', 'data-dismiss' => 'modal'),
    ));

    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t("menu", "Отмена"),
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>