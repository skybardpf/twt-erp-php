<?php /* @var $this Controller */

Yii::app()->clientScript->registerCssFile(CHtml::asset(Yii::app()->basePath.'/../static/css/main.css'));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="container">
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="<?=Yii::app()->homeUrl?>"><?=Yii::app()->name?></a>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
	<div class="span3">
		<?php
		$items = array(
			'main'           => array('label' => 'Главная', 'url' => Yii::app()->homeUrl),
			'legal.entities' => array('label' => 'Юридические лица', 'url' => $this->createUrl('/legal/entities/')),
			//'legal.Counterparties_groups' => array('label' => 'Группы контрагентов', 'url' => $this->createUrl('/legal/counterparties_groups/')),
			//'legal.Banks' => array('label' => 'Банки', 'url' => $this->createUrl('/legal/banks/')),
			'legal.Countries' => array('label' => 'Страны юрисдикции', 'url' => $this->createUrl('/legal/countries/')),
		);
		if (isset($items[$this->menu_elem])) {
			$items[$this->menu_elem]['active'] = true;
		}
		$this->widget('bootstrap.widgets.TbMenu', array(
				'type'      => 'tabs', // '', 'tabs', 'pills' (or 'list')
			'stacked'   => true, // whether this is a stacked menu
			'items'     => array_values($items)
		));
		unset($items); ?>
	</div>
		<div class="span9">
			<?php
			/*$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
				'links' => $this->breadcrumbs
			));*/?>
		<?=$content?>
	</div>
</div>
</div>

</body>
</html>
