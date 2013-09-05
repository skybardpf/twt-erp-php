<?php
/**
 * Форма удаления заинтересованного лица.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var CController $this
 * @var InterestedPersonAbstract $model
 */
?>

Вы действительно хотите <?= $model->deleted ? '<b>восстановить заинтересованное лицо</b>': '<b>удалить заинтересованное лицо</b>'?> «<?=CHtml::encode($model->person_name)?>»?

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
        'label'=> 'Нет',
        'htmlOptions' => array('name' => 'result', 'value' => 'no')
    ));

    $this->endWidget();
?>