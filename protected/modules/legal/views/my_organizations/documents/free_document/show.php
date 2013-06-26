<?php
/**
 * User: Skibardin A.A.
 * Date: 25.06.13
 *
 * @var $this       My_organizationsController
 * @var $freeDoc    FreeDocument
 */

//LegalEntities::getValues();
//LUser::getValues();
//
?>
<h2>Свободный документ "<?= $freeDoc->name; ?>"</h2>
<a href="<?=$this->createUrl('documents', array('id' => $this->organization->primaryKey))?>">Назад к списку</a>


<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'type'       => 'success',
    'label'      => 'Редактировать',
    'url'        => Yii::app()->getController()->createUrl("free_document", array('action' => 'update', 'id' => $freeDoc->primaryKey))
));

if (!$freeDoc->deleted) {
    echo "&nbsp;";
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'    => 'submit',
        'type'          => 'danger',
        'label'         => 'Удалить',
        'htmlOptions'   => array(
            'data-question'     => 'Вы уверены, что хотите удалить данный документ?',
            'data-title'        => 'Удаление документа',
            'data-url'          => $this->createUrl('/legal/my_organizations/free_document', array('action' => 'delete','id' => $freeDoc->primaryKey)),
            'data-redirect_url' => $this->createUrl('/legal/my_organizations/documents', array('id' => $this->organization->primaryKey)),
            'data-delete_item_element' => '1'
        )
    ));
}
?>
<div>
	<?php
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $freeDoc,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
            array('name' => 'type_yur',     'label' => 'Тип юр. лица'),
            array('name' => 'num',          'label' => 'Номер документа'),
			array('name' => 'name',         'label' => 'Название'),
			array('name' => 'id_yur',       'label' => 'Юр.Лицо', 'value' => $freeDoc->id_yur),
//			array('name' => 'id_yur',       'label' => 'Юр.Лицо', 'value' => $freeDoc->id_yur ? LegalEntities::$values[$freeDoc->id_yur] : 'Не указан'),
			array('name' => 'date',         'label' => 'Дата загрузки'),
			array('name' => 'expire',       'label' => 'Срок действия'),

		)
	));
	?>
</div>