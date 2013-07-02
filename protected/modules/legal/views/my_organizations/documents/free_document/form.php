<?php
/**
 * User: Skibardin A.A.
 * Date: 25.06.2013
 *
 * @var $this   My_organizationsController
 * @var $model    FreeDocument
 */

$this->beginContent('/my_organizations/show');

/** @var $form TbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'    => 'horizontalForm',
	'type'  => 'horizontal',
));

// Опции для JUI селектора даты
$jui_date_options = array(
	'options'=>array(
		'showAnim'=>'fold',
		'dateFormat' => 'yy-mm-dd',
	),
	'htmlOptions'=>array(
		'style'=>'height:20px;'
	)
);
?>
<h2><?=($model->primaryKey ? 'Редактирование ' : 'Создание ').'свободного документа'?></h2>
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=> 'submit',
    'type'      => 'primary',
    'label'     => 'Сохранить'
));
?>
&nbsp;
<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'link',
	'label'      => 'Отмена',
	'url'        => $model->primaryKey
						? $this->createUrl('show_free_document', array('id' => $model->primaryKey))
						: $this->createUrl('documents', array('id' => $this->organization->primaryKey))
));
?>

<?php
if ($error) {
    echo '<br/><br/>';
    echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div');
}
?>
<?= $form->errorSummary($model)?>

<fieldset>
<!--	--><?//= $form->dropDownListRow(
//            $model,
//            'type_yur',
//            CHtml::listData(array(
//                array('id' => 'Организации', 'type_yur' => 'Организации'),
//                array('id' => 'Контрагенты', 'type_yur' => 'Контрагенты')
//            ), 'id', 'type_yur')
//        );
//    ?>
	<?= $form->textFieldRow($model, 'num', array('class' => 'span6')); ?>
	<?= $form->textFieldRow($model, 'name', array('class' => 'span6')); ?>

	<?php /* date */?>
	<div class="control-group">
		<label class="control-label" for="FreeDocument_date">
            <?= $model->getAttributeLabel("date") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
        </label>

        <div class="controls">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
				array(
					'model'     => $model,
					'attribute' => 'date'
				), $jui_date_options
			)); ?>
		</div>
	</div>

	<?php /* expire */?>
	<div class="control-group">
		<label class="control-label" for="FreeDocument_expire">
            <?= $model->getAttributeLabel("expire") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
        </label>
		<div class="controls">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
				array(
					'model'     => $model,
					'attribute' => 'expire'
				), $jui_date_options
			)); ?>
		</div>
	</div>
	<?= $form->textAreaRow($model, 'comment', array('class' => 'span6')); ?>
</fieldset>
<?php $this->endWidget(); ?>
<?php $this->endContent(); ?>