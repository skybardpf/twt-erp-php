<?php
/**
 * Список контрагентов по группам. Информация разбита по вкладкам:
 * - Контрагенты
 * - Группы контрагентов
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Controller    $this
 * @var string        $content
 * @var string        $current_tab_menu
 */
?>

<div class="yur-tabs">
<?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'tabs',
        'stacked' => false,
        'items' => array(
            array(
                'label' => 'Контрагенты',
                'url'   => $this->createUrl('contractor/'),
                'active'=> ($current_tab_menu == 'contractor')
            ),
            array(
                'label' => 'Группы',
                'url'   => $this->createUrl('contractor_group/'),
                'active'=> ($current_tab_menu == 'contractor_group')
            ),
        )
    ));
?>
</div>

<div class="yur-content">
    <?= $content; ?>
</div>