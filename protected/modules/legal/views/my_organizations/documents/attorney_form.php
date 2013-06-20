<?php
/**
 * User: Forgon
 * Date: 11.06.2013 от Рождества Христова
 *
 * @var $this My_OrganizationsController
 * @var $doc PowerAttorneysLE
 */

$this->beginContent('/my_organizations/show');

/** @var $form TbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'horizontalForm',
	'type'=>'horizontal',
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
<h2><?=($doc->primaryKey ? 'Редактирование ' : 'Создание ').'доверенности'?></h2>
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label'=>'Сохранить')); ?>&nbsp;
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'link',
	'label'      => 'Отмена',
	'url'        => $doc->primaryKey
						? $this->createUrl('show_attorney', array('id' => $doc->primaryKey))
						: $this->createUrl('documents', array('id' => $this->organization->primaryKey))
)); ?>

<?php if ($error) echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div'); ?>
<?=$form->errorSummary($doc)?>

<fieldset>
	<?= $form->dropDownListRow( $doc, 'id_lico',     Individuals::getValues()); ?>
	<?= $form->textFieldRow(    $doc, 'num',         array('class' => 'span6')); ?>
	<?= $form->textFieldRow(    $doc, 'name',        array('class' => 'span6')); ?>
	<?= $form->dropDownListRow( $doc, 'typ_doc',     PowerAttorneysLE::getDocTypes()); ?>

	<?php /* date */?>
	<div class="control-group">
		<label class="control-label" for="PowerAttorneysLE_date"><?php echo $doc->getAttributeLabel("date"); ?></label>
		<div class="controls">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
				array(
					'model'     => $doc,
					'attribute' => 'date'
				), $jui_date_options
			)); ?>
		</div>
	</div>

	<?php /* expire */?>
	<div class="control-group">
		<label class="control-label" for="PowerAttorneysLE_expire"><?php echo $doc->getAttributeLabel("expire"); ?></label>
		<div class="controls">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
				array(
					'model'     => $doc,
					'attribute' => 'expire'
				), $jui_date_options
			)); ?>
		</div>
	</div>

	<?php /* break */?>
	<div class="control-group">
		<label class="control-label" for="PowerAttorneysLE_break"><?php echo $doc->getAttributeLabel("break"); ?></label>
		<div class="controls">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
				array(
					'model'     => $doc,
					'attribute' => 'break'
				), $jui_date_options
			)); ?>
		</div>
	</div>

	<?= $form->textAreaRow(     $doc, 'comment',     array('class' => 'span6')); ?>
</fieldset>
<?php $this->endWidget(); ?>
<?php $this->endContent(); ?>