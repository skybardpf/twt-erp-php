<?php
/**
 * User: Forgon
 * Date: 11.06.2013 от Рождества Христова
 *
 * @var $this   My_OrganizationsController
 * @var $model  FoundingDocument
 */

$this->beginContent('/my_organizations/show');

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'type'       => 'success',
    'label'      => 'Редактировать',
    'url'        => Yii::app()->getController()->createUrl("edit_founding", array('id' => $model->id))
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
			'data-url'          => $this->createUrl('/legal/my_organizations/delete_founding', array('id' => $model->primaryKey)),
			'data-redirect_url' => $this->createUrl('/legal/my_organizations/documents', array('id' => $this->organization->primaryKey)),
			'data-delete_item_element' => '1'
		)
	));
}
?>

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
<?php
$this->endContent();