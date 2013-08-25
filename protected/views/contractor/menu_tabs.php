<?php
/**
 * Просмотр информации об контрагенте. Иформация разбита по вкладкам.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Controller    $this
 * @var Contractor    $model
 * @var string        $content
 * @var string        $current_tab_menu
 */
?>

<?php
    echo '<h2>'.$model->name.'</h2>';
?>
<div class="yur-tabs">
<?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'tabs',
        'stacked' => false,
        'items' => array(
            array(
                'label' => 'Информация',
                'url'   => $this->createUrl('contractor/view', array('id' => $model->primaryKey)),
                'active'=> ($current_tab_menu == 'info')
            ),
            array(
                'label' => 'Доверенности',
                'url'   => $this->createUrl('power_attorney_contractor/list', array('cid' => $model->primaryKey)),
                'active'=> ($current_tab_menu == 'power_attorney')
            ),
        ),
    ));
?>
</div>

<div class="yur-content">
    <?= $content; ?>
</div>