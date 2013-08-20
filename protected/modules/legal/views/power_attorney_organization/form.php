<?php
/**
 * Редактирование/Добавление доверенности для контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Power_attorney_organizationController $this
 * @var PowerAttorneyForOrganization $model
 * @var Contractor $organization
 */
?>

<?php
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/jquery.json-2.4.min.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/jquery.fileDownload.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/legal/manage_files.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/legal/power_attorney/form.js');

echo '<h2>' . ($model->primaryKey ? 'Редактирование ' : 'Создание ') . 'доверенности</h2>';

/* @var $form MTbActiveForm */
$form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
    'id' => 'form-power-attorney',
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
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
    'url' => $model->primaryKey
        ? $this->createUrl('view', array('id' => $model->primaryKey))
        : $this->createUrl('power_attorney_contractor/list', array('cid' => $organization->primaryKey))
));
?>

<?php
if ($model->hasErrors()) {
    echo '<br/><br/>' . $form->errorSummary($model);
}
?>

<fieldset>
<?php
$jui_date_options = array(
    'language' => 'ru',
    'options' => array(
        'showAnim' => 'fold',
        'dateFormat' => 'yy-mm-dd',
        'changeMonth' => true,
        'changeYear' => true,
        'showOn' => 'button',
        'constrainInput' => 'true',
    ),
    'htmlOptions' => array(
        'style' => 'height:20px;'
    )
);

echo $form->dropDownListRow($model, 'id_lico', Individuals::model()->getDataNames($model->getForceCached()), array('class' => 'span6'));
echo $form->textFieldRow($model, 'nom', array('class' => 'span6'));
echo $form->textFieldRow($model, 'name', array('class' => 'span6'));
echo $form->dropDownListRow($model, 'typ_doc', PowerAttorneyForOrganization::getDocTypes(), array('class' => 'span6'));

echo CHtml::tag('div', array(
    'class' => 'model-info',
    'data-id' => $model->primaryKey,
    'data-class-name' => get_class($model)
));

echo $form->hiddenField($model, 'json_type_of_contract');
echo $form->hiddenField($model, 'json_exists_files');
echo $form->hiddenField($model, 'json_exists_scans');

/**
 * Заполняем выбранные виды договоров.
 */
$data_type_of_contract = array();
$contract_types = ContractType::model()->listNames($model->getForceCached());
foreach ($model->type_of_contract as $f) {
    $data_type_of_contract[] = array(
        'id' => $f . '_id',
        'type' => (!isset($contract_types[$f])) ? '---' : $contract_types[$f],
        'delete' => $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'button',
            'type' => 'primary',
            'label' => 'Удалить',
            'htmlOptions' => array(
                'class' => 'del-type-contract',
                'data-id' => $f
            )
        ), true)
    );
}

$data_files = array();
$data_scans = array();
if ($model->primaryKey) {
    $path = Yii::app()->user->getId() . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . $model->primaryKey;
    $path_scans = $path . DIRECTORY_SEPARATOR . MDocumentCategory::SCAN;
    $path_files = $path . DIRECTORY_SEPARATOR . MDocumentCategory::FILE;

    foreach ($model->list_files as $f) {
        $data_files[] = array(
            'id' => $f . '_id',
            'filename' => CHtml::link($f, '#', array(
                'class' => 'download_file',
                'data-type' => MDocumentCategory::FILE
            )),
            'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'primary',
                'label' => 'Удалить',
                'htmlOptions' => array(
                    'class' => 'delete_file',
                    'data-type' => MDocumentCategory::FILE,
                    'data-filename' => $f
                )
            ), true)
        );
    }
    foreach ($model->list_scans as $f) {
        $data_scans[] = array(
            'id' => $f . '_id',
            'filename' => CHtml::link($f, '#', array(
                'class' => 'download_file',
                'data-type' => MDocumentCategory::SCAN
            )),
            'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'primary',
                'label' => 'Удалить',
                'htmlOptions' => array(
                    'class' => 'delete_file',
                    'data-type' => MDocumentCategory::SCAN,
                    'data-filename' => $f
                )
            ), true)
        );
    }
}
?>

<div class="control-group">
    <?= $form->labelEx($model, 'type_of_contract', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        $this->widget('bootstrap.widgets.TbGridView',
            array(
                'id' => 'grid-type-contract',
                'type' => 'striped bordered condensed',
                'dataProvider' => new CArrayDataProvider($data_type_of_contract),
                'template' => "{items}",
                'columns' => array(
                    array(
                        'name' => 'type',
                        'header' => 'Название',
//                        'type' => 'raw',
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
        echo $form->error($model, 'type_of_contract');
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=> 'button',
            'type' => 'primary',
            'label' => 'Добавить',
            'htmlOptions' => array(
                'class' => 'add-type-contract',
            )
        ));
        ?>
    </div>
</div>

<?php /** date */ ?>
<div class="control-group">
    <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
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
        $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
            array(
                'model' => $model,
                'attribute' => 'expire'
            ), $jui_date_options
        ));
        echo $form->error($model, 'expire');
        ?>
    </div>
</div>

<?php /* break */ ?>
<div class="control-group">
    <?= $form->labelEx($model, 'break', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
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
    echo $this->renderPartial('/_files/grid_files',
        array(
            'data' => $data_files,
            'model' => $model,
            'attribute' => 'list_files',
            'attribute_files' => 'upload_files',
            'grid_id' => 'grid-files',
            'accept_ext' => '',
        ),
        true
    );
    echo $this->renderPartial('/_files/grid_files',
        array(
            'data' => $data_scans,
            'model' => $model,
            'attribute' => 'list_scans',
            'grid_id' => 'grid-scans',
            'attribute_files' => 'upload_scans',
            'accept_ext' => '',
        ),
        true
    );
?>
</fieldset>

<?php
    $this->endWidget();

    echo $this->renderPartial('/_files/download_hint', array(), true);

    /**
    * Модальное окошко для выбора вида договора
    */
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalWindow'));
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4><?=Yii::t("menu", "Выберите")?></h4>
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

