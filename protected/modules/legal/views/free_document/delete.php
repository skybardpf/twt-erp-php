<?php
/**
 * Форма удаления свободного документа.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Free_documentController $this
 * @var FreeDocument            $model
 * @var Organization            $organization
 */
?>

Вы действительно хотите <?= $model->deleted ? '<b>восстановить свободный документ</b>': '<b>удалить свободный документ</b>'?> «<?= CHtml::encode($model->name); ?>»?

<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'news-delete-form',
        'type'=>'horizontal',
    ));
    echo '<br/>';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'danger',
        'label'=>'Да',
        'htmlOptions' => array('name' => 'result', 'value' => 'yes')
    ));
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'success',
        'label'=>'Нет',
        'htmlOptions' => array('name' => 'result', 'value' => 'no')
    ));

    $this->endWidget();
?>