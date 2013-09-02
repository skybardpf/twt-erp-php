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
            'url' => $this->createUrl(
                'interested_person/index',
                array(
                    'org_id' => $organization->primaryKey,
                    'type' => MPageTypeInterestedPerson::SHAREHOLDER,
                )
            ),
            'active' => ($menu_tab == MPageTypeInterestedPerson::SHAREHOLDER)
        ),
        array(
            'label' => 'Руководители',
            'url' => $this->createUrl(
                'interested_person/index',
                array(
                    'org_id' => $organization->primaryKey,
                    'type' => MPageTypeInterestedPerson::LEADER,
                )
            ),
            'active' => ($menu_tab == MPageTypeInterestedPerson::LEADER)
        ),
        array(
            'label' => 'Менеджеры',
            'url' => $this->createUrl(
                'interested_person/index',
                array(
                    'org_id' => $organization->primaryKey,
                    'type' => MPageTypeInterestedPerson::MANAGER,
                )
            ),
            'active' => ($menu_tab == MPageTypeInterestedPerson::MANAGER)
        ),
        array(
            'label' => 'Секретари',
            'url' => $this->createUrl(
                'interested_person/index',
                array(
                    'org_id' => $organization->primaryKey,
                    'type' => MPageTypeInterestedPerson::SECRETARY,
                )
            ),
            'active' => ($menu_tab == MPageTypeInterestedPerson::SECRETARY),
        ),
    )
));
?>
</div>
<div class="yur-content">
	<?= $content; ?>
</div>
