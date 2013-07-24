<?php
/**
 *  Документы -> Доверенности. Форма редактирования.
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 *
 *  @var $this          Power_attorney_leController
 *  @var $model         PowerAttorneysLE
 *  @var $organization  Organizations
 */
?>

<script>
    window.controller_name = '<?= $this->getId(); ?>';
</script>
<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/form_manage_files.js');

    echo '<h2>'.($model->primaryKey ? 'Редактирование ' : 'Создание ').'доверенности</h2>';

    /* @var $form MTbActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id'    => 'model-form-form',
        'type'  => 'horizontal',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        ),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'submit',
        'type'      => 'primary',
        'label'     => 'Сохранить'
    ));
    echo '&nbsp;';
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
    } elseif ($model->hasErrors()) {
        echo '<br/><br/>';
        echo $form->errorSummary($model);
    }
?>

<fieldset>
<?php
    // Опции для JUI селектора даты
    $jui_date_options = array(
        'options'=>array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
        ),
        'htmlOptions'=>array(
            'style' => 'height:20px;'
        )
    );

    echo $form->dropDownListRow($model, 'id_lico', Individuals::getValues(), array('class' => 'span6'));
    echo $form->textFieldRow($model, 'nom', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'name', array('class' => 'span6'));
//    if (!$model->getprimaryKey()){
//        echo $form->dropDownListRow($model, 'type_yur', PowerAttorneysLE::getYurTypes(), array('class' => 'span6'));
//    }
    echo $form->dropDownListRow($model, 'typ_doc', PowerAttorneysLE::getDocTypes(), array('class' => 'span6'));
    //
    // Список видов договоров будет здесь.
    //
?>
<?php /** date */ ?>
<div class="control-group">
    <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
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
    <?= $form->labelEx($model, 'expire', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'expire'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php /* break */?>
<div class="control-group">
    <?= $form->labelEx($model, 'break', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'break'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php
    echo $form->textAreaRow($model, 'comment', array('class' => 'span6'));
?>

<?php
    $div_scans  = '';
    $div_files  = '';
    if ($model->primaryKey){
        $files = UploadFile::getListFiles(UploadFile::CLIENT_ID, get_class($model), $model->primaryKey);
        foreach ($files as $f){
            if ($f['type'] == UploadFile::TYPE_FILE_SCANS){
                $div_scans .= CHtml::tag('div',
                    array(
                        'class' => 'block'
                    ),
                    CHtml::link($f['filename'], '#', array('class' => 'download_scan', 'data-file_id' => $f['id'])) .
                    '&nbsp;&nbsp;&nbsp;' .
                    CHtml::link('', '#', array('class' => 'icon-remove', 'data-file_id' => $f['id']))
                );
            } elseif ($f['type'] == UploadFile::TYPE_FILE_FILES){
                $div_files .= CHtml::tag('div',
                    array(
                        'class' => 'block'
                    ),
                    CHtml::link($f['filename'], '#', array('class' => 'download_file', 'data-file_id' => $f['id'])) .
                    '&nbsp;&nbsp;&nbsp;' .
                    CHtml::link('', '#', array('class' => 'icon-remove', 'data-file_id' => $f['id']))
                );
            }
        }
    }
?>
    <div class="control-group">
        <?= $form->labelEx($model, 'list_scans', array('class' => 'control-label')); ?>
        <div class="controls bordered" id="list_uploaded_scans">
            <?php
                echo (empty($div_scans)) ? '' : $div_scans.'<br/>';
                $this->widget('CMultiFileUpload', array(
                    'name' => 'upload_scans',
        //                'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
                    'duplicate' => 'Duplicate file!', // useful, i think
                    'denied' => 'Invalid file type', // useful, i think
                    'htmlOptions' => array( 'multiple' => 'multiple', ),
                ));
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'list_files', array('class' => 'control-label')); ?>
        <div class="controls bordered" id="list_uploaded_files">
            <?php
            echo (empty($div_files)) ? '' : $div_files.'<br/>';
            $this->widget('CMultiFileUpload', array(
                'name' => 'upload_files',
//                'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
                'duplicate' => 'Duplicate file!', // useful, i think
                'denied' => 'Invalid file type', // useful, i think
                'htmlOptions' => array( 'multiple' => 'multiple', ),
            ));
            ?>
        </div>
    </div>

</fieldset>

<?php $this->endWidget(); ?>