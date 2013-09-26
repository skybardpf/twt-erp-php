<?php
/**
 * Список видов договоров
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
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
    'label' => 'Сохранить',
));
echo '&nbsp;';
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'label' => 'Отмена',
    'url' => ($model->primaryKey
        ? $this->createUrl('view', array('id' => $model->primaryKey))
        : $this->createUrl('index')
    )
));

if ($model->hasErrors()) {
    echo '<br/><br/>';
    echo $form->errorSummary($model);
}

$statuses = $model->getStatuses();
echo $form->textFieldRow($model, 'name');
$attributes = $model->listAttributes();
foreach ($attributes as $a){
    echo $form->radioButtonListInlineRow($model, $a, $statuses);
}
$this->endWidget();