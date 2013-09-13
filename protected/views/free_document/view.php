<?php
/**
 * Просмотр свободного документа.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Free_documentController $this
 * @var FreeDocument $model
 * @var Organization $organization
 */

Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/jquery.fileDownload.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/legal/manage_files.js');
?>

<h2>Свободный документ</h2>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'type' => 'success',
    'label' => 'Редактировать',
    'url' => $this->createUrl("edit", array('id' => $model->primaryKey))
));

if (!$model->deleted) {
    echo "&nbsp;";
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/legal/delete_item.js');

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'danger',
        'label' => 'Удалить',
        'htmlOptions' => array(
            'data-question' => 'Вы уверены, что хотите удалить данный документ?',
            'data-title' => 'Удаление документа',
            'data-url' => $this->createUrl('delete', array('id' => $model->primaryKey)),
            'data-redirect_url' => $this->createUrl('documents/list', array('org_id' => $organization->primaryKey)),
            'data-delete_item_element' => '1'
        )
    ));
}
?>

<br/><br/>
<div>
    <?php
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            array(
                'name' => 'num',
                'label' => 'Номер'
            ),
            array(
                'name' => 'name',
                'label' => 'Наименование'
            ),
            array(
                'name' => 'date',
                'label' => 'Дата начала действия'
            ),
            array(
                'name' => 'expire',
                'label' => 'Срок действия'
            ),
        )
    ));
    ?>
</div>

<fieldset>
    <?php
    echo CHtml::tag('div', array(
        'class' => 'model-info',
        'data-id' => $model->primaryKey,
        'data-class-name' => get_class($model)
    ));

    if (!empty($model->list_files)) {
        echo '<h4>Файлы:</h4>';
        foreach ($model->list_files as $f) {
            echo CHtml::link($f, '#',
                    array(
                        'class' => 'download_file',
                        'data-type' => MDocumentCategory::FILE,
                    )
                ) . '<br/>';
        }
    }
    if (!empty($model->list_scans)) {
        echo '<h4>Сканы:</h4>';
        foreach ($model->list_scans as $f) {
            echo CHtml::link($f, '#', array(
                        'class' => 'download_file',
                        'data-type' => MDocumentCategory::SCAN,
                    )
                ) . '<br/>';
        }
    }
    ?>
</fieldset>

<div id="preparing-file-modal" title="Подготовка файла..." style="display: none;">
    Подготавливается файл для скачивания, подождите...

    <div class="ui-progressbar-value ui-corner-left ui-corner-right"
         style="width: 100%; height:22px; margin-top: 20px;"></div>
</div>
<div id="error-modal" title="Error" style="display: none;">
    Возникли проблемы при подготовке файла, повторите попытку
</div>