<?php
/**
 * Список заинтересованных лиц. Разбито на 4 вкладки:
 * - Номинальные акционеры
 * - Руководители
 * - Менеджеры
 * - Секретари
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_personController $this
 * @var Organization $organization
 * @var string $menu_tab
 * @var string $content
 */
?>
<div class="yur-tabs">
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'tabs',
    'stacked' => false,
    'items' => array(
        array(
            'label' => 'Номинальные акционеры',
            'url' => $this->createUrl('interested_person/index', array('id' => $organization->primaryKey)),
            'active' => ($menu_tab == 'shareholder')
        ),
        array(
            'label' => 'Руководители',
            'url' => $this->createUrl('documents/list', array('org_id' => $organization->primaryKey)),
            'active' => ($menu_tab == 'leader')
        ),
        array(
            'label' => 'Менеджеры',
            'url' => $this->createUrl('settlement_account/list', array('org_id' => $organization->primaryKey)),
            'active' => ($menu_tab == 'manager')
        ),
        array(
            'label' => 'Секретари',
            'url' => $this->createUrl('interested_person/index', array('org_id' => $organization->primaryKey)),
            'active' => ($menu_tab == 'secretary'),
        ),
    )
));
?>
</div>
<div class="yur-content">
	<?= $content; ?>
</div>
