<?php
/**
 * Редактирование/Добавление доверенности для контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Power_attorney_contractorController $this
 * @var PowerAttorneyForContractor          $model
 * @var Contractor                          $organization
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
        'id' => 'form-power-attorney',
        'type' => 'horizontal',
        'enableAjaxValidation' => false,
        'clientOptions' => array(
            'validateOnChange' => true,
        ),
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        ),
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Сохранить'
    ));
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'label' => 'Отмена',
        'url'  => $model->primaryKey
            ? $this->createUrl('view', array('id' => $model->primaryKey))
            : $this->createUrl('power_attorney_contractor/list', array('cid' => $organization->primaryKey))
    ));
?>

<?php
    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
    }
?>

<fieldset>
<?php
    $jui_date_options = array(
        'language' => 'ru',
        'options'=>array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
            'showOn' => 'button',
            'constrainInput' => 'true',
        ),
        'htmlOptions'=>array(
            'style' => 'height:20px;'
        )
    );

    echo $form->dropDownListRow($model, 'id_lico', Individuals::model()->getDataNames($model->getForceCached()), array('class' => 'span6'));
    echo $form->textFieldRow($model, 'nom', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'name', array('class' => 'span6'));
?>
<?php /** date */ ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'date'
                ), $jui_date_options
            ));
            echo $form->error($model, 'date');
        ?>
        </div>
    </div>

<?php /* expire */ ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'expire', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'expire'
                ), $jui_date_options
            ));
            echo $form->error($model, 'expire');
        ?>
        </div>
    </div>

<?php /* break */?>
    <div class="control-group">
        <?= $form->labelEx($model, 'break', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'break'
                ), $jui_date_options
            ));
            echo $form->error($model, 'break');
        ?>
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

    $data_files = array();
    $data_scans = array();
?>
    <div class="control-group">
        <?= $form->labelEx($model, 'list_files', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            $this->widget('bootstrap.widgets.TbGridView',
                array(
                    'id' => 'grid-files',
                    'type' => 'striped bordered condensed',
                    'dataProvider' => new CArrayDataProvider($data_files),
                    'template' => "{items}",
    //                    'htmlOptions' => array(
    //                        'data-id' => ($model->primaryKey) ? $model->primaryKey : ''
    //                    ),
                    'columns' => array(
                        array(
                            'name' => 'fio',
                            'header' => 'Название',
                            'type' => 'raw',
                            'htmlOptions' => array(
                                'style' => 'width: 90%',
                            )
                        ),
                        array(
                            'name' => 'delete',
                            'header' => '',
                            'type' => 'raw'
                        ),
                    )
                )
            );
            $this->widget('CMultiFileUpload', array(
                'name' => 'upload_files',
//                'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
                'duplicate' => 'Файл с таким именем уже выбран!',
                'denied' => 'Неправильный тип файла',
                'htmlOptions' => array(
                    'multiple' => 'multiple',
                ),
            ));
            echo $form->error($model, 'list_files');
        ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'list_scans', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('bootstrap.widgets.TbGridView',
                array(
                    'id' => 'grid-scans',
                    'type' => 'striped bordered condensed',
                    'dataProvider' => new CArrayDataProvider($data_scans),
                    'template' => "{items}",
//                    'htmlOptions' => array(
//                        'data-id' => ($model->primaryKey) ? $model->primaryKey : ''
//                    ),
                    'columns' => array(
                        array(
                            'name' => 'fio',
                            'header' => 'Название',
                            'type' => 'raw',
                            'htmlOptions' => array(
                                'style' => 'width: 90%',
                            )
                        ),
                        array(
                            'name' => 'delete',
                            'header' => '',
                            'type' => 'raw'
                        ),
                    )
                )
            );
            $this->widget('CMultiFileUpload', array(
                'name' => 'upload_scans',
//                'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
                'duplicate' => 'Скан с таким именем уже выбран!',
                'denied' => 'Неправильный тип скана',
                'htmlOptions' => array(
                    'multiple' => 'multiple',
                ),
            ));
            echo $form->error($model, 'list_scans');
            ?>
        </div>
    </div>

</fieldset>

<?php $this->endWidget(); ?>