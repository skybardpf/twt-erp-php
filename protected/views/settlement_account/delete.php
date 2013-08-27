<?php
/**
 * Банковские счета -> Удаление.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Settlement_accountsController  $this
 * @var SettlementAccount              $model
 * @var Organization                  $organization
 */
?>
Вы действительно хотите <?= $model->deleted ? '<b>восстановить банковсйй счет</b>': '<b>удалить банковсйй счет</b>'?> «<?= CHtml::encode($model->name); ?>»?

<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'news-delete-form',
        'type'=>'horizontal',
    ));
    echo '<br/>';
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