<?php
/**
 * Форма удаления контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var $this   ContractorController
 * @var $model  Contractor
 */
?>

Вы действительно хотите <?= $model->deleted ? '<b>восстановить контрагента</b>': '<b>удалить контрагента</b>'?> «<?=CHtml::encode($model->name)?>»?

<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'news-delete-form',
        'type'=>'horizontal',
    ));

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'danger',
        'label'=>'Да',
        'htmlOptions' => array('name' => 'result', 'value' => 'yes')
    ));

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'success',
        'label'=>'Нет',
        'htmlOptions' => array('name' => 'result', 'value' => 'no')
    ));

    $this->endWidget();
?>