<?php
/* @var $this IndividualsController */
/* @var $element Individuals */

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
			array('name' => 'family',           'label' => 'Фамилия'),
			array('name' => 'name',             'label' => 'Имя'),
			array('name' => 'parent_name',      'label' => 'Отчество'),
			array('name' => 'ser_nom_pass',     'label' => 'Серия-номер паспорта'),
			array('name' => 'date_pass',        'label' => 'Дата выдачи паспорта'),
			array('name' => 'organ_pass',       'label' => 'Орган, выдавший паспорт'),
			array('name' => 'date_exp_pass',    'label' => 'Срок действия паспорта'),
			array('name' => 'ser_nom_passrf',   'label' => 'Серия-номер паспорта РФ'),
			array('name' => 'date_passrf',      'label' => 'Дата выдачи паспорта РФ'),
			array('name' => 'organ_passrf',     'label' => 'Орган, выдавший паспорт РФ'),
			array('name' => 'date_exp_passrf',  'label' => 'Срок действия паспорта РФ'),
			array('name' => 'group_code',       'label' => 'Группа физ.лиц'),
			array('name' => 'phone',            'label' => 'Номер телефона'),
			array('name' => 'adres',            'label' => 'Адрес'),
			array('name' => 'email',            'label' => 'E-mail'),

			/*array('name' => 'eng_name',     'label' => 'Английское наименование'),
			array('name' => 'country',      'label' => 'Страна юрисдикции', 'type' => 'raw', 'value' => $element->country ? Countries::$values[$element->country] : 'Не указана'),


			'id'              => '#',
			'name'            => 'Имя',
			'family'          => 'Фамилия',
			'parent_name'     => 'Отчество',
			'fullname'        => 'ФИО',

			'ser_nom_pass'    => 'Серия-номер паспорта',
			'date_pass'       => 'Дата выдачи пасопрта',
			'organ_pass'      => 'Орган, выдавший паспорт',
			'date_exp_pass'   => 'Срок действия паспорта',

			'ser_nom_passrf'  => 'Серия-номер паспорта',
			'date_passrf'     => 'Дата выдачи пасопрта',
			'organ_passrf'    => 'Орган, выдавший паспорт',
			'date_exp_passrf' => 'Срок действия паспорта',

			'group_code'      => 'Группа физ.лиц',

			'resident'        => 'Резидент РФ',

			'phone'           => 'Номер телефона',

			'adres'           => 'Адрес',
			'email'           => 'E-mail',

			'deleted'       => 'Помечен на удаление'

			array('name' => 'resident',     'label' => 'Не является резидентом РФ', 'type' => 'raw', 'value' => $element->resident ? 'Да' : 'Нет'),
			array('name' => 'type_no_res',  'label' => 'Тип нерезидента', 'type' => 'raw', 'value' => $element->type_no_res ? $element->NonResidentValues[$element->type_no_res] : 'Не указан'),
			array('name' => 'contragent',   'label' => 'Контрагент', 'type' => 'raw', 'value' => $element->contragent ? 'Сторонее лицо' : 'Cобственное лицо'),
			array('name' => 'parent',       'label' => 'Группа контрагентов', 'type' => 'raw', 'value' => $element->parent ? CounterpartiesGroups::$values[$element->parent] : 'Не указана'),
			array('name' => 'comment',      'label' => 'Комментарий'),
			array('name' => 'inn',          'label' => 'ИНН'),
			array('name' => 'inn',          'label' => 'КПП'),
			array('name' => 'ogrn',         'label' => 'ОГРН'),
			array('name' => 'yur_address',  'label' => 'Адрес юридический'),
			array('name' => 'fact_address', 'label' => 'Адрес фактический'),
			array('name' => 'reg_nom',      'label' => 'Регистрационный номер'),
			array('name' => 'sert_nom',     'label' => 'Номер сертификата о регистрации'),
			array('name' => 'sert_date',    'label' => 'Дата сертификата о регистрации'),
			array('name' => 'vat_nom',      'label' => 'VAT-номер'),
			array('name' => 'profile',      'label' => 'Основной вид деятельности'),*/
		)
	));
	?>
</div>
