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

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => 'Сохранить'
));
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

echo $form->textFieldRow($model, 'name');

$statuses = $model->getStatuses();
echo $form->radioButtonListInlineRow($model, 'contractor', $statuses);
echo $form->radioButtonListInlineRow($model, 'title', $statuses);
echo $form->radioButtonListInlineRow($model, 'number', $statuses);
echo $form->radioButtonListInlineRow($model, 'date', $statuses);
echo $form->radioButtonListInlineRow($model, 'date_expire', $statuses);
echo $form->radioButtonListInlineRow($model, 'contract_status', $statuses);
echo $form->radioButtonListInlineRow($model, 'place_of_contract', $statuses);
echo $form->radioButtonListInlineRow($model, 'type_of_prolongation', $statuses);
echo $form->radioButtonListInlineRow($model, 'notice_end_of_contract', $statuses);
echo $form->radioButtonListInlineRow($model, 'currency', $statuses);
echo $form->radioButtonListInlineRow($model, 'sum_contract', $statuses);
echo $form->radioButtonListInlineRow($model, 'sum_month', $statuses);
echo $form->radioButtonListInlineRow($model, 'responsible_contract', $statuses);
echo $form->radioButtonListInlineRow($model, 'role', $statuses);
echo $form->radioButtonListInlineRow($model, 'organization_signatories', $statuses);
echo $form->radioButtonListInlineRow($model, 'contractor_signatories', $statuses);
echo $form->radioButtonListInlineRow($model, 'third_parties_signatories', $statuses);
echo $form->radioButtonListInlineRow($model, 'place_of_court', $statuses);
echo $form->radioButtonListInlineRow($model, 'comment', $statuses);
echo $form->radioButtonListInlineRow($model, 'scans', $statuses);
echo $form->radioButtonListInlineRow($model, 'original_documents', $statuses);

$this->endWidget();

?>