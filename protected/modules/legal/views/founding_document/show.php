<?php
/**
 * User: Forgon
 * Date: 25.02.13
 *
 * @var $this Ledocument_typeController
 */
LegalEntities::getValues();
LEDocumentType::getValues();
?>
<h2>Учредительный документ "<?=$model->name?>"</h2>
<a href="<?=$this->createUrl('index')?>">Назад к списку</a>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'name',         'label' => 'Название'),
			array('name' => 'id_yur',       'label' => 'Юр.Лицо', 'value' => isset(LegalEntities::$values[$model->id_yur])? LegalEntities::$values[$model->id_yur] : 'Не указан'),
			array('name' => 'date',         'label' => 'Дата загрузки'),
			array('name' => 'expire',       'label' => 'Срок действия'),
			array('name' => 'typ_doc',      'label' => 'Тип документа', 'value' => isset(LEDocumentType::$values[$model->typ_doc]) ? LEDocumentType::$values[$model->typ_doc] : 'Не указан'),
		)
	));
	?>
</div>