<?php
/* @var $this BanksController */

$this->breadcrumbs=array(
	$this->controller_title,
);
?>
<h2><?=$this->controller_title?></h2>
<div>
	<?php
	if ($elements) {

		$gridDataProvider = new CArrayDataProvider($elements);

		$this->widget('bootstrap.widgets.TbGridView', array(
			'type'=>'striped',
			'dataProvider' => $gridDataProvider,
			'columns'=>array(
				array('name' => 'id', 'header'=>'#'),
				array('name' => 'name', 'header'=>'Название',),
				array('name' => 'country', 'header' => 'Страна юрисдикции'),
				array('name' => 'city', 'header' => 'Город'),
				array('name' => 'address', 'header' => 'Адрес'),
				array('name' => 'phone', 'header' => 'Телефон'),
				array('name' => 'bik', 'header' => 'БИК код'),
				array('name' => 'cor_sh', 'header' => 'Корр. счет'),
				array('name' => 'swift', 'header' => 'SWIFT код'),
			),
		));
	} else {
		echo 'Ни одного банка не зарегистрировано.';
	}
	?>
</div>
