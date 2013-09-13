<?php
/**
 * Список видов договоров
 * @var Contract_typeController $this
 * @var ContractType $model
 */
?>
    <h2><?= ($model->primaryKey ? 'Редактирование' : 'Создание') . ' вида договора' ?></h2>

<?php
/** @var $form MTbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
    'id' => 'form-type-contract',
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnChange' => true,
    ),
));

if ($model->is_standart){
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'primary',
        'label' => 'Сохранить',
        'disabled' => true,
    ));
} else {
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Сохранить',
    ));
}

echo '&nbsp;';
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'label' => 'Отмена',
    'url' => $this->createUrl('index'),
));

if ($model->hasErrors()) {
    echo '<br/><br/>';
    echo $form->errorSummary($model);
}

$options = ($model->is_standart) ? array('disabled' => true) : array();
$statuses = $model->getStatuses();
echo $form->textFieldRow($model, 'name', $options);
echo $form->radioButtonListInlineRow($model, 'contractor', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'title', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'number', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'date', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'date_expire', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'contract_status', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'place_of_contract', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'type_of_prolongation', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'notice_end_of_contract', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'currency', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'sum_contract', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'sum_month', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'responsible_contract', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'role', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'organization_signatories', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'contractor_signatories', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'third_parties_signatories', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'place_of_court', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'comment', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'scans', $statuses, $options);
echo $form->radioButtonListInlineRow($model, 'original_documents', $statuses, $options);

$this->endWidget();