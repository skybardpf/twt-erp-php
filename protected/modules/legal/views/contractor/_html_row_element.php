<?php
/**
 * Возращает html. Новая строка для вставки в таблицу подписантов и довереностей
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var ContractController  $this
 * @var string              $person_id
 * @var string              $doc_id
 * @var string              $person_name
 * @var string              $doc_name
 */
?>

<td style="width: 45%">
<?= CHtml::link($person_name, $this->createUrl('Individual/view', array('id' => $person_id))); ?>
</td>
<td style="width: 45%">
    <?= CHtml::link($doc_name, $this->createUrl('power_attorney_le/view', array('id' => $doc_id))); ?>
</td>
<td>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'button',
        'type' => 'primary',
        'label' => 'Удалить',
        'htmlOptions' => array(
            'class' => 'del-signatory',
            'data-id' => $person_id.'_'.$doc_id,
        )
    ));
    ?>
</td>