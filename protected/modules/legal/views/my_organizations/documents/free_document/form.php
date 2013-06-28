<?php
/**
 * User: Skibardin A.A.
 * Date: 25.06.2013
 *
 * @var $this   My_organizationsController
 * @var $doc    FreeDocument
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
<h2><?=($doc->primaryKey ? 'Редактирование ' : 'Создание ').'свободного документа'?></h2>
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
	'url'        => $doc->primaryKey
						? $this->createUrl('free_document', array('action' => 'show', 'id' => $doc->primaryKey))
						: $this->createUrl('documents', array('id' => $this->organization->primaryKey))
));
?>

<?php
if ($error) {
    echo '<br/><br/>';
    echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div');
}
?>
<?= $form->errorSummary($doc)?>

<fieldset>
<!--	--><?//= $form->dropDownListRow(
//            $doc,
//            'type_yur',
//            CHtml::listData(array(
//                array('id' => 'Организации', 'type_yur' => 'Организации'),
//                array('id' => 'Контрагенты', 'type_yur' => 'Контрагенты')
//            ), 'id', 'type_yur')
//        );
//    ?>
	<?= $form->textFieldRow($doc, 'num', array('class' => 'span6')); ?>
	<?= $form->textFieldRow($doc, 'name', array('class' => 'span6')); ?>

	<?php /* date */?>
	<div class="control-group">
		<label class="control-label" for="FreeDocument_date">
            <?= $doc->getAttributeLabel("date") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
        </label>

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
		<label class="control-label" for="FreeDocument_expire">
            <?= $doc->getAttributeLabel("expire") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
        </label>
		<div class="controls">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
				array(
					'model'     => $doc,
					'attribute' => 'expire'
				), $jui_date_options
			)); ?>
		</div>
	</div>
	<?= $form->textAreaRow($doc, 'comment', array('class' => 'span6')); ?>
</fieldset>
<?php $this->endWidget(); ?>
<?php $this->endContent(); ?>