<?php
/**
 *  Просмотр учредительного документа.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.2013
 *
 *  @var $this          Founding_documentsController
 *  @var $model         FoundingDocument
 *  @var $organization  Organization
 */
?>

<h2>Учредительный документ</h2>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'type'       => 'success',
    'label'      => 'Редактировать',
    'url'        => $this->createUrl("edit", array('id' => $model->primaryKey))
));

if (!$model->deleted) {
	echo "&nbsp;";
	Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'    => 'submit',
		'type'          => 'danger',
		'label'         => 'Удалить',
		'htmlOptions'   => array(
			'data-question'     => 'Вы уверены, что хотите удалить данный учредительный документ?',
			'data-title'        => 'Удаление учредительного документа',
			'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
			'data-redirect_url' => $this->createUrl('documents/list', array('org_id' => $organization->primaryKey)),
			'data-delete_item_element' => '1'
		)
	));
}
?>

<br/><br/>
<div>
	<?php
	$doc_types = LEDocumentType::getValues();
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data'       => $model,
		'attributes' => array(
			array(
				'name'  => 'typ_doc',
				//'label' => 'Гражданство',
				'type'  => 'raw',
				'value' => ($model->typ_doc && isset($doc_types[$model->typ_doc])) ? $doc_types[$model->typ_doc] : '&mdash;',
			),
			'num', 'name', 'date', 'expire', 'comment', 
		)
	));
	?>
</div>

<fieldset>
    <?= CHtml::link('Скачать электронную версию', '#') . '<br/>'; ?>
    <?= CHtml::link('Скачать сканы', '#') . '<br/>'; ?>
    <?= CHtml::link('Сгенерировать документ', '#') . '<br/>'; ?>
</fieldset>