<?php
/**
 * Форма редактирования данных о событие(мероприятие).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var My_eventsController | Calendar_eventsController $this
 * @var Event           $model
 * @var Organizations   $organization
 */
?>

<script>
    window.controller_name = '<?= $this->getId(); ?>';
</script>

<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/form_manage_files.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/my_events/form.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/jquery.json-2.4.min.js');

    if ($this instanceof Calendar_eventsController){
        $url_list = $this->createUrl('list', array("org_id" => $organization->primaryKey, "id" => $model->primaryKey));
        $url_view = $this->createUrl('view', array("org_id" => $organization->primaryKey, "id" => $model->primaryKey));
    } else {
        $url_list = $this->createUrl('index');
        $url_view = $this->createUrl('view', array('id' => $model->primaryKey));
    }

    echo '<h2>'.($model->primaryKey ? 'Редактирование' : 'Создание').' события</h2>';

    /* @var $form MTbActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id'    => 'form-my-events',
        'type'  => 'horizontal',
        'enableAjaxValidation' => true,
        'enableClientValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
        ),
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
        'label' => 'Отмена',
        'url' => $model->primaryKey ? $url_view : $url_list
    ));

    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
    }
?>

<fieldset>
<?php
    // Опции для JUI селектора даты
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

    /**
     * Определяем, что показывать страны или список организаций.
     */
    if (is_bool($model->for_yur)){
        $model->for_yur = ($model->for_yur === true) ? Event::FOR_ORGANIZATIONS : (($model->for_yur === false) ? Event::FOR_JURISDICTION : '');
    }
    echo $form->textFieldRow($model, 'name');
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
     * Заполняем блок, выбранных юр. лиц.
     */
    $div_yur = '';
    $data = array();
    if (!empty($model->list_yur)){
        /**
         * Список огранизаций и контрагентов
         */
        $organizations = Organizations::getValues();
        $contractors = Contractor::getValues();
        foreach ($model->list_yur as $v){
            if ($v['type_yur'] == 'Организации'){
                if (isset($organizations[$v['id_yur']])){
                    $data[] = array(
                        'id' => 'o_'.$v['id_yur'],
                        'name' => CHtml::link($organizations[$v['id_yur']], $this->createUrl('my_organizations/view', array('id' => $v['id_yur']))),
                        'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'button',
                            'type' => 'primary',
                            'label' => 'Удалить',
                            'htmlOptions' => array(
                                'class' => 'del-element',
                                'data-id' => $v['id_yur'],
                                'data-type' => 'organization'
                            )
                        ), true)
                    );
                }
            } elseif($v['type_yur'] == 'Контрагенты'){
                if (isset($contractors[$v['id_yur']])){
                    $data[] = array(
                        'id' => 'c_'.$v['id_yur'],
                        'name' => CHtml::link($contractors[$v['id_yur']], $this->createUrl('contractor/view', array('id' => $v['id_yur']))),
                        'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'button',
                            'type' => 'primary',
                            'label' => 'Удалить',
                            'htmlOptions' => array(
                                'class' => 'del-element',
                                'data-id' => $v['id_yur'],
                                'data-type' => 'contractor'
                            )
                        ), true)
                    );
                }
            }
        }
    }
    $div_yur = $this->widget('bootstrap.widgets.TbGridView',
        array(
            'id' => 'grid-organizations',
            'type' => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider($data),
            'template' => "{items}",
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => 'Организация',
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
        ),
        true
    );
    $div_yur .= $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'button',
        'type' => 'primary',
        'label' => 'Добавить организацию',
        'htmlOptions' => array(
            'class' => 'add-organization',
            'data-type' => 'organization'
        )
    ), true);

    /**
     * Заполняем блок, выбранных стран.
     */
    $div_countries = '';
    $data = array();
    if (!empty($model->countries)){
        $countries = Countries::getValues();
        foreach($model->countries as $v){
            if (isset($countries[$v])){
                $data[] = array(
                    'id' => $v,
                    'name' => $countries[$v],
                    'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'button',
                        'type' => 'primary',
                        'label' => 'Удалить',
                        'htmlOptions' => array(
                            'class' => 'del-element',
                            'data-id' => $v,
                            'data-type' => 'country'
                        )
                    ), true)
                );
            }
        }
    }
    $div_countries = $this->widget('bootstrap.widgets.TbGridView',
        array(
            'id' => 'grid-countries',
            'type' => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider($data),
            'template' => "{items}",
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => 'Страна',
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
        ),
        true
    );
    $div_countries .= $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'button',
        'type' => 'primary',
        'label' => 'Добавить страну',
        'htmlOptions' => array(
            'class' => 'add-country',
            'data-type' => 'country'
        )
    ), true);

    echo $form->hiddenField($model, 'json_organizations');
    echo $form->hiddenField($model, 'json_contractors');
    echo $form->hiddenField($model, 'json_countries');
?>
    <div class="<?= $class_yur; ?>" id="for_yur">
        <?= CHtml::label('Юр. лица <span class="required">*</span>', 'for_yur', array('class' => 'control-label required')); ?>
        <div class="controls" id='for_yur_list'>
            <?= $div_yur; ?>
        </div>
    </div>

    <div class="<?= $class_countries; ?>" id="for_countries">
        <?= CHtml::label('Страны <span class="required">*</span>', 'for_countries', array('class' => 'control-label required')); ?>
        <div class="controls" id='for_countries_list'>
            <?= $div_countries; ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'event_date', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model'     => $model,
                    'attribute' => 'event_date'
                ), $jui_date_options
            ));
            echo $form->error($model, 'event_date');
        ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'notification_date', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model'     => $model,
                    'attribute' => 'notification_date'
                ), $jui_date_options
            ));
            echo $form->error($model, 'notification_date');
        ?>
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
        <h4><?=Yii::t("menu", "Выберите из списка")?></h4>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t("menu", "Сохранить"),
            'url' => '#',
            'htmlOptions' => array('class'=>'button_save', 'data-dismiss'=>'modal'),
        ));

        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t("menu", "Отмена"),
            'url' => '#',
            'htmlOptions' => array('data-dismiss'=>'modal'),
        ));
        ?>
    </div>
<?php $this->endWidget(); ?>

<?php
    // Модальное окошко для выбора страны
//    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'dataModalCountries'));
?>
<!--    <div class="modal-header">-->
<!--        <a class="close" data-dismiss="modal">×</a>-->
<!--        <h4>--><?//=Yii::t("menu", "Выберите страну")?><!--</h4>-->
<!--    </div>-->
<!--    <div class="modal-body"></div>-->
<!--    <div class="modal-footer">-->
<!--        --><?php
//        $this->widget('bootstrap.widgets.TbButton', array(
//            'label' => Yii::t("menu", "Сохранить"),
//            'url'   => '#',
//            'htmlOptions' => array('class'=>'button_save', 'data-dismiss'=>'modal'),
//        ));
//
//        $this->widget('bootstrap.widgets.TbButton', array(
//            'label' => Yii::t("menu", "Отмена"),
//            'url'   => '#',
//            'htmlOptions' => array('data-dismiss'=>'modal'),
//        ));
//        ?>
<!--    </div>-->
<?php //$this->endWidget(); ?>