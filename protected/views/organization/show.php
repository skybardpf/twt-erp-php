<?php
/**
 * Просмотр Организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var OrganizationController      $this
 * @var Organization                $organization
 * @var string                      $cur_tab
 */
?>
<h1><?= CHtml::encode($organization->name); ?></h1>
<div class="yur-tabs">
<?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
        'stacked'=> false, // whether this is a stacked menu
        'items' => array(
            array(
                'label' => 'Информация',
                'url'   => $this->createUrl('organization/view', array('id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'info')
            ),
            array(
                'label' => 'Документы',
                'url'   => $this->createUrl('documents/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'documents')
            ),
            array(
                'label' => 'Банковские счета',
                'url'   => $this->createUrl('settlement_account/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'settlements')
            ),
            array(
                'label' => 'Заинтересованные лица',
                'url'   => $this->createUrl('interested_person/index', array('org_id' => $organization->primaryKey)),
                'active' => ($cur_tab == 'interested_person'),
            ),
            array(
                'label' => 'Бенефициары',
                'url'   => $this->createUrl('interested_person_beneficiary/index', array('org_id' => $organization->primaryKey, 'org_type' => $organization->type)),
                'active' => ($cur_tab == 'beneficiary'),
            ),
            array(
                'label' => 'Календарь событий',
                'url'   => $this->createUrl('calendar_events/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'calendar_events'),
            ),
            array(
                'label' => 'Договоры',
                'url'   => $this->createUrl('contract/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'contract')
            ),
        )
    ));
?>
</div>
<div class="yur-content">
	<?= $content; ?>
</div>