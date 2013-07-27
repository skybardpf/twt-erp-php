<?php
/**
 * User: Forgon
 * Date: 10.06.2013 от Рождества Христова
 *
 * @var $this IndividualsController
 * @var $model Individuals
 * @var $tab_content string
 */
?>
<h2><?=$model->family?> <?=$model->name?> <?=$model->parent_name?></h2>
<div class="yur-tabs">
	<?php $this->widget('bootstrap.widgets.TbMenu', array(
		'type'    => 'tabs', // '', 'tabs', 'pills' (or 'list')
		'stacked' => false, // whether this is a stacked menu
		'items'   => array(
			array(
				'label'  => 'Информация',
				'url'    => $this->createUrl('view', array('id' => $model->id)),
				'active' => $this->cur_tab == 'view'
			),
			array(
				'label'  => 'Корзина акционирования',
				'url'    => $this->createUrl('cart', array('id' => $model->id)),
				'active' => $this->cur_tab == 'cart'
			),
		),
	)); ?>
</div>
<div class="yur-content">
	<?=$tab_content?>
</div>
