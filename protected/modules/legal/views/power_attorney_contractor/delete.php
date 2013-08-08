<?php
/**
 * Форма удаления доверенности.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Controller              $this
 * @var PowerAttorneyAbstract   $model
 */
?>

Вы действительно хотите <?= $model->deleted ? '<b>восстановить доверенность</b>': '<b>удалить доверенность</b>'?> «<?=CHtml::encode($model->name); ?>»?
<br/><br/>
<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'form-delete',
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
?>