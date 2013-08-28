<?php
/**
 * Просмотр Юр.Лица
 *
 * User: Forgon
 * Date: 23.04.2013 от рождества Христова
 *
 * @var OrganizationController      $this
 * @var Organization                $organization
 * @var string                      $cur_tab
 */
?>
<h1><?= Chtml::encode($organization->name); ?></h1>
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
            /*
            array(
                'label' => 'Заинтересованные лица',
                'url'   => $this->createUrl('interested_persons/index', array('org_id' => $organization->primaryKey)),
                'active' => ($cur_tab == 'interested_persons'),
                'itemOptions' => array('class'=>'narrow')
            ),*/
            array(
                'label' => 'Календарь событий',
                'url'   => $this->createUrl('calendar_events/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'calendar_events'),
                'itemOptions' => array('class'=>'narrow narrower')
            ),
            /*
            array(
                'label' => 'Договоры',
                'url'   => $this->createUrl('contract/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'contract')
            ),*/
        )
    ));
?>
</div>
<div class="yur-content">
	<?= $content; ?>
</div>