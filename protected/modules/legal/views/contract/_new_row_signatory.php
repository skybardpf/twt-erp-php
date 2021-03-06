<?php
/**
 * Возращает html. Новую строка для вставки в таблицу подписантов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var ContractController  $this
 * @var string              $id
 * @var string              $name
 * @var string              $type
 */
?>

<td style="width: 90%">
    <?= CHtml::link($name, $this->createUrl('individuals/view', array('id' => $id))); ?>
</td>
<td>
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'button',
        'type' => 'primary',
        'label' => 'Удалить',
        'htmlOptions' => array(
            'class' => 'del-signatory',
            'data-id' => $id,
            'data-type' => $type,
        )
    ));
?>
</td>