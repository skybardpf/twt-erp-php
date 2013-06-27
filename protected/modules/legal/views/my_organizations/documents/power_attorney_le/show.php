<?php
/**
 *  Документы -> Доверенности
 *  User: Skibardin A.A.
 *  Date: 26.06.13
 *
 *  @var $this       My_organizationsController
 *  @var $doc        PowerAttorneysLE
 */
?>

<h2>Доверенность "<?= $doc->name; ?>"</h2>
<a href="<?=$this->createUrl('documents', array('id' => $this->organization->primaryKey))?>">Назад к списку</a>

<?
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'type'       => 'success',
    'label'      => 'Редактировать',
    'url'        => $this->createUrl("power_attorney_le", array('action' => 'update', 'id' => $doc->primaryKey))
));

if (!$doc->deleted) {
    echo "&nbsp;";
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'    => 'submit',
        'type'          => 'danger',
        'label'         => 'Удалить',
        'htmlOptions'   => array(
            'data-question'     => 'Вы уверены, что хотите удалить данный документ?',
            'data-title'        => 'Удаление документа',
            'data-url'          => $this->createUrl('power_attorney_le', array('action' => 'delete','id' => $doc->primaryKey)),
            'data-redirect_url' => $this->createUrl('documents', array('id' => $this->organization->primaryKey)),
            'data-delete_item_element' => '1'
        )
    ));
}
?>
<br/>
<div>
	<?php
    $individuals = Individuals::getValues();
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $doc,
		'attributes'=>array(
			array('name' => 'id',           'label' => '#'),
			array('name' => 'id_lico',      'label' => 'На кого оформлена', 'value' => (isset($individuals[$doc->id_lico]) ? $individuals[$doc->id_lico] : 'Не задано')),
            array('name' => 'nom',          'label' => 'Номер'),
			array('name' => 'name',         'label' => 'Название'),
            array('name' => 'typ_doc',      'label' => 'Вид'),

			array('name' => 'date',         'label' => 'Дата начала действия'),
//			array('name' => 'loaded',       'label' => 'Дата загрузки документа'),
			array('name' => 'expire',       'label' => 'Срок действия'),
//			array('name' => 'break',        'label' => 'Дата отмены'),


		)
	));
	?>
</div>
