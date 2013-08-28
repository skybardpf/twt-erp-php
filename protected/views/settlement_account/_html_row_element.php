<?php
/**
 * Возращает html. Новая строка для вставки в таблицу управляющих персон.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Settlement_accountController $this
 * @var string $id
 * @var string $name
 */
?>

<td style="width: 90%">
    <?= CHtml::link($name, $this->createUrl('individual/view', array('id' => $id))); ?>
</td>
<td>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'primary',
        'label' => 'Удалить',
        'htmlOptions' => array(
            'class' => 'del-element',
            'data-id' => $id,
        )
    ));
    ?>
</td>