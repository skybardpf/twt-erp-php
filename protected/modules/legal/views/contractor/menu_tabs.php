<?php
/**
 * Просмотр информации об контрагенте. Иформация разбита по вкладкам.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var ContractorController    $this
 * @var Contractor              $model
 * @var string                  $content
 */
?>

<?php
    echo '<h2>'.$model->name.'</h2>';
?>
<div class="yur-tabs">
<?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
        'stacked'=>false, // whether this is a stacked menu
        'items'=>array(
            array(
                'label' => 'Информация',
                'url'   => $this->createUrl('view', array('id' => $model->primaryKey)),
                'active'=> ($this->current_tab_menu == $this::TAB_MENU_INFO)
            ),
            array(
                'label' => 'Документы',
                'url'   => $this->createUrl('contractor_power_attorney/index', array('cid' => $model->primaryKey)),
                'active'=> ($this->current_tab_menu == $this::TAB_MENU_POWER_ATTORNEY)
            ),
        ),
    ));
?>
</div>

<div class="yur-content">
    <?= $content; ?>
</div>