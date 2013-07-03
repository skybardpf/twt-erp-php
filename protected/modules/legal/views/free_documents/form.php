<?php
/**
 *  User: Skibardin A.A.
 *  Date: 25.06.2013
 *
 *  @var $this          Free_documentsController
 *  @var $model         FreeDocument
 *  @var $organization  Organizations
 */
?>

<?php
/** @var $form MTbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
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
		'style'=>'height:20px;',
        'class' => 'span6'
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
                        ? $this->createUrl('view', array('id' => $model->primaryKey))
                        : $this->createUrl('documents/list', array('org_id' => $organization->primaryKey))
    ));
?>

<?php
    if ($error) {
        echo '<br/><br/>';
        echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div');
    } elseif ($model->getErrors()) {
        echo '<br/><br/>';
        echo $form->errorSummary($model);
    }
?>

<fieldset>
	<?= $form->textFieldRow($model, 'num', array('class' => 'span6')); ?>
	<?= $form->textFieldRow($model, 'name', array('class' => 'span6')); ?>

	<?php /* date */?>
	<div class="control-group">
        <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
        <div class="controls">
			<?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                    array(
                        'model'     => $model,
                        'attribute' => 'date'
                    ), $jui_date_options
                ));
            ?>
		</div>
	</div>

	<?php /* expire */?>
	<div class="control-group">
        <?= $form->labelEx($model, 'expire', array('class' => 'control-label')); ?>
		<div class="controls">
			<?php
                $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                    array(
                        'model'     => $model,
                        'attribute' => 'expire'
                    ), $jui_date_options
                ));
            ?>
		</div>
	</div>

	<?= $form->textAreaRow($model, 'comment', array('class' => 'span6')); ?>
</fieldset>

<?php $this->endWidget(); ?>