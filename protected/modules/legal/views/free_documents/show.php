<?php
/**
 * User: Forgon
 * Date: 25.02.13
 *
 * @var $this Ledocument_typeController
 */
LegalEntities::getValues();
LUser::getValues();
$this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes'=>array(
		array('name' => 'id',           'label' => '#'),
		array('name' => 'name',         'label' => 'Название'),
		array('name' => 'id_yur',       'label' => 'Юр.Лицо', 'value' => $model->id_yur ? LegalEntities::$values[$model->id_yur] : 'Не указан'),
		array('name' => 'date',         'label' => 'Дата загрузки'),
		array('name' => 'expire',       'label' => 'Срок действия'),
		array('name' => 'nom',          'label' => 'Номер документа'),
		array('name' => 'from_user',    'label' => 'Тип документа', 'value' => $model->from_user ? 'От пользователя' : 'От администратора'),
		array('name' => 'user',         'label' => 'Юр.Лицо', 'value' => $model->user ? LUser::$values[$model->user] : 'Не указан'),
		//"return":"[{\"user\":\"\",
	)
));
?>