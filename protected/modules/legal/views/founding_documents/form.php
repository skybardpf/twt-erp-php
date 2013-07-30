<?php
/**
 *  Редактирование учредительного документа.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.2013
 *
 *  @var $this          Founding_documentsController
 *  @var $model         FoundingDocument
 *  @var $error         string
 *  @var $organization  Organization
 *
 *  @var $photos        XUploadForm     XXX
 *  @var $scans         UploadScan      XXX
 */

/** @var $form TbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
	'id'=>'horizontalForm',
	'type'=>'horizontal',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
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
<h2><?=($model->primaryKey ? 'Редактирование ' : 'Создание ').'учредительного документа'?></h2>
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label'=>'Сохранить')); ?>&nbsp;
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType' => 'link',
	'label'      => 'Отмена',
	'url'        => $model->primaryKey
                    ? $this->createUrl('view', array('id' => $model->primaryKey))
                    : $this->createUrl('documents/list', array('org_id' => $organization->primaryKey))
)); ?>

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
	<?= $form->dropDownListRow($model, 'typ_doc', LEDocumentType::getValues()); ?>
	<?= $form->textFieldRow($model, 'num', array('class' => 'span6')); ?>
	<?= $form->textFieldRow($model, 'name', array('class' => 'span6')); ?>

	<?php /* date */?>
	<div class="control-group">
        <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
		<div class="controls">
			<?php
                $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
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

<!--    <div class="control-group">-->
<!--        <label class="control-label" for="FoundingDocument_scans">-->
<!--            --><?//= $model->getAttributeLabel("scans"); ?>
<!--        </label>-->
<!--        <div class="controls">-->
<!--            --><?php
//            $this->widget( 'xupload.XUpload', array(
//                'url' => $this->createUrl( "upload_founding", array('id' => $model->primaryKey)),
//                //our XUploadForm
//                'model' => $photos,
//                //We set this for the widget to be able to target our own form
//                'htmlOptions' => array('id'=>'FoundingDocument-form-scans'),
//                'attribute' => 'file',
//                'multiple' => true,
//                //Note that we are using a custom view for our widget
//                //Thats becase the default widget includes the 'form'
//                //which we don't want here
////                    'formView' => 'documents/founding_documents/form_upload',
//            ));
//            ?>
<!--        </div>-->
<!--    </div>-->

<!--    <div class="control-group">-->
<!--        <label class="control-label" for="FoundingDocument_files">-->
<!--            --><?//= $model->getAttributeLabel("files"); ?>
<!--        </label>-->
<!--        <div class="controls">-->
<!--            --><?php
//            $this->widget( 'xupload.XUpload', array(
//                'url' => $this->createUrl( "upload_founding", array('id' => $model->primaryKey)),
//                //our XUploadForm
//                'model' => $photos,
//                //We set this for the widget to be able to target our own form
//                'htmlOptions' => array('id'=>'FoundingDocument-form-files'),
//                'attribute' => 'file',
//                'multiple' => true,
//                //Note that we are using a custom view for our widget
//                //Thats becase the default widget includes the 'form'
//                //which we don't want here
////                    'formView' => 'documents/founding_documents/form_upload',
//            ));
//            ?>
<!--        </div>-->
<!--    </div>-->
<?php $this->endWidget(); ?>