<?php
/**
 *  Просмотр свободного документа.
 *
 *  User: Skibardin A.A.
 *  Date: 25.06.13
 *
 *  @var $this          Free_documentsController
 *  @var $model         FreeDocument
 *  @var $organization  Organizations
 */
?>

<h2>Свободный документ</h2>

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
                'data-question'     => 'Вы уверены, что хотите удалить данный документ?',
                'data-title'        => 'Удаление документа',
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
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes'=>array(
            array('name' => 'num',          'label' => 'Номер документа'),
			array('name' => 'name',         'label' => 'Название'),
			array('name' => 'date',         'label' => 'Дата начала действия'),
			array('name' => 'expire',       'label' => 'Срок действия'),
		)
	));
	?>
</div>