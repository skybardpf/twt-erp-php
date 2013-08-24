<?php
/**
 * Возращает html. Новая строка для вставки в таблицу видов договоров.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Power_attorney_organizationController $this
 * @var string $id
 * @var string $name
 */
?>

<td style="width: 90%">
    <?= $name; ?>
</td>
<td>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'primary',
        'label' => 'Удалить',
        'htmlOptions' => array(
            'class' => 'del-type-contract',
            'data-id' => $id
        )
    ));
    ?>
</td>