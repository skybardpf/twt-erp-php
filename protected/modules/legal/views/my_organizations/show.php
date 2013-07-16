<?php
/**
 * Просмотр Юр.Лица
 *
 * User: Forgon
 * Date: 23.04.2013 от рождества Христова
 *
 * @var $this           Controller
 * @var $organization   Organizations
 * @var $cur_tab        string
 */
?>
<h1><?=$organization->name?></h1>
<div class="yur-tabs">
    <?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
        'stacked'=>false, // whether this is a stacked menu
        'items'=>array(
            array(
                'label' => 'Информация',
                'url'   => $this->createUrl('my_organizations/view', array('id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'info')
            ),
            array(
                'label' => 'Документы',
                'url'   => $this->createUrl('documents/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'documents')
            ),
            array(
                'label' => 'Банковские счета',
                'url'   => $this->createUrl('settlement_accounts/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'settlements')
            ),
            array(
                'label' => 'Заинтересованные лица',
                'url'   => $this->createUrl('interested_persons/index', array('org_id' => $organization->primaryKey)),
                'active' => ($cur_tab == 'interested_persons'),
                'itemOptions' => array('class'=>'narrow')
            ),
            array(
                'label' => 'Календарь событий',
                'url'   => $this->createUrl('my_events/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'my_events'),
                'itemOptions' => array('class'=>'narrow narrower')
            ),
            array(
                'label' => 'Договоры',
                'url'   => $this->createUrl('contracts/list', array('org_id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'contracts')
            ),
            /*array('label'=>'Расчётные счета', 'url'=>$this->createUrl('settlements', array('id' => $model->id)), 'active'=> $cur_tab == 'settlements'),
            array(
                'label'=>'Заинтересованные лица и бенефициары',
                'url'=>$this->createUrl('benefits', array('id' => $model->id)),
                'active'=> $cur_tab == 'benefits',
                'itemOptions' => array('class'=>'narrow')
            ),
            array(
                'label'=>'Календарь событий',
                'url'=>$this->createUrl('my_events', array('id' => $model->id)),
                'active'=> $cur_tab == 'my_events',
                'itemOptions' => array('class'=>'narrow narrower')
            ),
            array('label'=>'Договоры', 'url'=>$this->createUrl('contracts', array('id' => $model->id)), 'active'=> $cur_tab == 'contract'),*/
        ),
    ));
    ?>
</div>
<div class="yur-content">
	<?= $content; ?>
</div>