<?php
/* @var $this BeneficiaryController */
/* @var $element Beneficiary */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Просмотр',
);
?>
<h2><?=$element->role?></h2>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $element,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'role',         'label' => 'Роль'),
		)
	));
	?>
</div>
