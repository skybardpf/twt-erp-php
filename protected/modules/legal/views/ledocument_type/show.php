<?php
/**
 * User: Forgon
 * Date: 25.02.13
 *
 * @var $this Ledocument_typeController
 */
$gridDataProvider = new CArrayDataProvider($model->list_of_countries, array('keyField' => 'country'));
Countries::getValues();
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
						array('name' => 'country', 'header'=>'Страна', 'type' => 'raw', 'value' => '($data["country"] && isset(Countries::$values[$data["country"]])) ? Countries::$values[$data["country"]] : "Не указана"'),
						array('name' => 'name_in_country', 'header'=>'Название документа',),
					)
				), true)
			)
		)
	)
);
?>