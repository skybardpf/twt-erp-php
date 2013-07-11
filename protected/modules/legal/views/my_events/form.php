<?php
/**
 * Форма редактирования данных о событие(мероприятие).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var $this   My_eventsController
 * @var $model  Event
 */
?>

<script>
    window.controller_name = '<?= $this->getId(); ?>';
</script>
<?php
    Yii::app()->clientScript->registerScriptFile('/static/js/jquery.json-2.4.min.js');
    Yii::app()->clientScript->registerScriptFile('/static/js/legal/my_events/form.js');
    Yii::app()->clientScript->registerScriptFile('/static/js/legal/form_manage_files.js');

    echo '<h2>'.($model->primaryKey ? 'Редактирование' : 'Создание').' события</h2>';

    /* @var $form MTbActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id'    => 'form-my-events',
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
        'url' => $model->primaryKey
            ? $this->createUrl('view', array('id' => $model->primaryKey))
            : $this->createUrl('index')
    ));

    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
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

    /**
     * Определяем, что показывать страны или список организаций.
     */
    if (is_bool($model->for_yur)){
        $model->for_yur = ($model->for_yur === true) ? Event::FOR_ORGANIZATIONS : (($model->for_yur === false) ? Event::FOR_JURISDICTION : '');
    }
    echo $form->textFieldRow($model, 'name', array('class' => 'span6'));
    echo $form->radioButtonListInlineRow($model, 'for_yur', Event::getTypes());

    $class_yur = 'control-group hide';
    $class_countries = 'control-group hide';
    if ($model->for_yur == Event::FOR_ORGANIZATIONS){
        $class_yur = 'control-group';
        $class_countries = 'control-group hide';
    } elseif ($model->for_yur == Event::FOR_JURISDICTION) {
        $class_yur = 'control-group hide';
        $class_countries = 'control-group';
    }

    /**
     * Список огранизаций
     */
    $div = '';
    $organizations = Organizations::getValues();
    $contractors = Contractor::getValues();

    if (!empty($model->list_yur)){
        foreach ($model->list_yur as $v){
            if ($v['type_yur'] == 'Организации'){
                if (isset($organizations[$v['id_yur']])){
                    $div .= CHtml::tag('div',
                        array(
                            'class' => 'block',
                            'data-type-org' => 'organization',
                            'data-id' => $v['id_yur']
                        ),
                        CHtml::link($organizations[$v['id_yur']], '#', array('class' => 'view_organization')) .
                        '&nbsp;&nbsp;&nbsp;' .
                        CHtml::link('', '#', array('class' => 'icon-remove'))
                    );
                }
            } elseif($v['type_yur'] == 'Контрагенты'){
                if (isset($contractors[$v['id_yur']])){
                    $div .= CHtml::tag('div',
                        array(
                            'class' => 'block',
                            'data-type-org' => 'contractor',
                            'data-id' => $v['id_yur']
                        ),
                        CHtml::link($contractors[$v['id_yur']], '#', array('class' => 'view_contractor', 'data-contractor-id' => $v['id_yur'])) .
                        '&nbsp;&nbsp;&nbsp;' .
                        CHtml::link('', '#', array('class' => 'icon-remove', 'data-contractor-id' => $v['id_yur']))
                    );
                }
            }
        }
    }
    echo $form->hiddenField($model, 'json_organizations');
    echo $form->hiddenField($model, 'json_contractors');
?>
    <div class="<?= $class_yur; ?>" id="for_yur">
        <?= CHtml::label('Юр. лица <span class="required">*</span>', 'for_yur', array('class' => 'control-label required')); ?>
<!--        <div class="controls" id="list_yur">-->
<!--            Добавьте юр. лиц-->
<!--        </div>-->
        <div class="controls" id='for_yur_list'>
            <?= $div; ?>
        </div>
        <div class="controls">
            <button class="btn" id="data-add-yur" data-loading-text="..." type="button">Добавить</button>
        </div>
    </div>

    <div class="<?= $class_countries; ?>" id="for_countries">
        <?= CHtml::label('Страны <span class="required">*</span>', 'for_countries', array('class' => 'control-label required')); ?>
    <div class="controls">
    <?php
        echo CHtml::dropDownList('Event[countries]', $model->countries, Countries::getValues(), array(
            'class' => 'span6'
        ));
    ?>
    </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'event_date', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model'     => $model,
                    'attribute' => 'event_date'
                ), $jui_date_options
            )); ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'notification_date', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model'     => $model,
                    'attribute' => 'notification_date'
                ), $jui_date_options
            )); ?>
        </div>
    </div>

<?php
    echo $form->dropDownListRow($model, 'period',Event::getPeriods(), array('class' => 'span6'));
    echo $form->textAreaRow($model, 'description', array('class' => 'span6'));
?>
<?php
    $div_files  = '';
    if ($model->primaryKey){
        $files = UploadFile::getListFiles(UploadFile::CLIENT_ID, get_class($model), $model->primaryKey, UploadFile::TYPE_FILE_FILES);
        foreach ($files as $f){
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
?>
    <div class="control-group">
        <?= $form->labelEx($model, 'files', array('class' => 'control-label')); ?>
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

<?php
// Модальное окошко для выбора физ. лица
$this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'dataModal'));
?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h4><?=Yii::t("menu", "Выберите юр. лицо")?></h4>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t("menu", "Сохранить"),
            'url'   => '#',
            'htmlOptions' => array('class'=>'button_save', 'data-dismiss'=>'modal'),
        ));

        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t("menu", "Отмена"),
            'url'   => '#',
            'htmlOptions' => array('data-dismiss'=>'modal'),
        ));
        ?>
    </div>
<?php $this->endWidget(); ?>