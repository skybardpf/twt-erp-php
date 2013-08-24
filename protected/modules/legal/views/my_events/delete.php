<?php
/**
 * Форма удаления события(мероприятия).
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var $this   My_eventsController
 * @var $model  Event
 */
?>

Вы действительно хотите <?= $model->deleted ? '<b>восстановить мероприятие</b>': '<b>удалить мероприятие</b>'?> «<?=CHtml::encode($model->name)?>»?
<br/><br/>
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
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'success',
        'label'=>'Нет',
        'htmlOptions' => array('name' => 'result', 'value' => 'no')
    ));

    $this->endWidget();
?>