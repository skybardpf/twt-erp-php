<?php
/**
 * User: Forgon
 * Date: 25.02.13
 *
 * @var $this Ledocument_typeController
 */

Yii::app()->clientScript->registerCss('LEDocumentTypeDetailView', 'table.table-striped th {text-align: left;}');

$gridDataProvider = new CArrayDataProvider($model->list_of_countries, array('keyField' => 'country'));
Country::getValues();
?>
<h2>Тип документа "<?=$model->name_of_doc?>"</h2>
<a href="<?=$this->createUrl('index')?>">Назад к списку</a>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'name_of_doc',  'label' => 'Название'),

			array(
				'name' => 'list_of_countries',
				'label' => 'Названия в странах',
				'type' => 'raw',
				'value' => $this->widget(
					'bootstrap.widgets.TbGridView',
					array(
						'type' => 'striped',
						'dataProvider' => $gridDataProvider,
						'enablePagination' => false,
						'enableSorting' => false,
						'template' => '{items}',
						'columns'=>array(
							array('name' => 'country', 'header'=>'Страна', 'type' => 'raw', 'value' => '($data["country"] && isset(Country::$values[$data["country"]])) ? Country::$values[$data["country"]] : "Не указана"'),
							array('name' => 'name_in_country', 'header'=>'Название документа',),
						),
					), true)
				)
			)
		)
	);
	?>
</div>