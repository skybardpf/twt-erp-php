<?php
/* @var $this EntitiesController */
/* @var $element LegalEntities */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/entities/'),
	'Просмотр',
);
?>
<h2><?=$element->name?></h2>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $element,
		'attributes'=>array(
			array('name' => 'id',                   'label' => '#'),
			array('name' => 'name',                 'label' => 'Название'),
			array('name' => 'description',          'label' => 'Описание'),
			array('name' => 'event_date',           'label' => 'event_date'),
			array('name' => 'notification_date',    'label' => 'notification_date'),
			array('name' => 'period',               'label' => 'Периодичность'),
			/*
			    user:,
                period:Месяц,
                id:000000002,
                deleted:false,
                notification_date:2013-03-01,
                event_date:2013-03-14,
                description:всывсыфвсфыс,
                made_by_user:false,
                name:Тест2,
                files:
					file1:D:_DOCUMENTS_07 IT DepartmentPRICE_1C.XLS,
                    file2:D:_DOCUMENTS_07 IT DepartmentТестовое ТЗ v2.doc

			'id'                => '#',                   // +
			'name'              => 'Название',            // +
			'deleted'           => 'На удаление',
			'made_by_user'      => 'Пользовательское',
			'user'              => 'Пользователь',
			'files'             => 'Файлы',
			'event_date'        => '',
			'notification_date' => '',
			'period'            => 'Периодичность',
			'description'       => 'Описание',
			*/
		)
	));
	?>
</div>
