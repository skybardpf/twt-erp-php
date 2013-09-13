<?php
/**
 * Удаление вида договора
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Contract_typeController $this
 * @var ContractType $model
 */
?>
    Вы действительно хотите <?= $model->deleted ? '<b>восстановить вид договора</b>' : '<b>удалить вид договора</b>' ?> «<?= CHtml::encode($model->name); ?>»?

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'delete-form',
    'type' => 'horizontal',
));
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'danger',
    'label' => 'Да',
    'htmlOptions' => array('name' => 'result', 'value' => 'yes')
));
echo '&nbsp;';
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'success',
    'label' => 'Нет',
    'htmlOptions' => array('name' => 'result', 'value' => 'no')
));
$this->endWidget();