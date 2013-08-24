<?php
/**
 * Возращает html. Новую строка для вставки в таблицу подписантов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractController  $this
 * @var string              $id
 * @var string              $name
 * @var string              $type
 */
?>

<td style="width: 90%">
    <?= CHtml::link($name, $this->createUrl('Individual/view', array('id' => $id))); ?>
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