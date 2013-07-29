<?php
/**
 * Возращает html. Новая строка для вставки в таблицу организации.
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
<?php
    if ($type == 'organization'){
        echo CHtml::link($name, $this->createUrl('organization/view', array('id' => $id)));
    } else {
        echo $name;
    }
?>
</td>
<td>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'button',
        'type' => 'primary',
        'label' => 'Удалить',
        'htmlOptions' => array(
            'class' => 'del-element',
            'data-id' => $id,
            'data-type' => $type
        )
    ));
    ?>
</td>