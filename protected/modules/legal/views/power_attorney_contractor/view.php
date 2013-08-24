<?php
/**
 *  Просмотр доверенности для контрагента.
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

<h2>Доверенность</h2>

<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/jquery.fileDownload.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/manage_files.js');

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type' => 'success',
        'label' => 'Редактировать',
        'url' => $this->createUrl("edit", array('id' => $model->primaryKey))
    ));

    if (!$model->deleted) {
        echo "&nbsp;";
        Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');

        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'    => 'submit',
            'type'          => 'danger',
            'label'         => 'Удалить',
            'htmlOptions'   => array(
                'data-question'     => 'Вы уверены, что хотите удалить данный документ?',
                'data-title'        => 'Удаление документа',
                'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
                'data-redirect_url' => $this->createUrl('list', array('cid' => $organization->primaryKey)),
                'data-delete_item_element' => '1'
            )
        ));
    }
?>

<br/><br/>
<div>
<?php
    $persons = Individual::model()->getDataNames($model->getForceCached());
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes'=>array(
            array(
                'name' => 'id_lico',
                'type' => 'raw',
                'label' => 'На кого оформлена',
                'value' => (isset($persons[$model->id_lico])) ? CHtml::link($persons[$model->id_lico], $this->createUrl('Individual/view', array('id' => $model->id_lico))) : '---'
            ),
            array(
                'name' => 'nom',
                'label' => 'Номер'
            ),
            array(
                'name' => 'name',
                'label' => 'Название'
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

    echo CHtml::tag('div', array(
        'class' => 'model-info',
        'data-id' => $model->primaryKey,
        'data-class-name' => get_class($model)
    ));

    if (!empty($model->list_files)){
        echo '<h4>Файлы:</h4>';
        foreach($model->list_files as $f){
            echo CHtml::link($f, '#',
                array(
                    'class' => 'download_file',
                    'data-type' => MDocumentCategory::FILE,
                )
            ) . '<br/>';
        }
    }
    if (!empty($model->list_scans)){
        echo '<h4>Сканы:</h4>';
        foreach($model->list_scans as $f){
            echo CHtml::link($f, '#', array(
                    'class' => 'download_file',
                    'data-type' => MDocumentCategory::SCAN,
                )
            ) . '<br/>';
        }
    }
?>
<div id="preparing-file-modal" title="Подготовка файла..." style="display: none;">
    Подготавливается файл для скачивания, подождите...

    <div class="ui-progressbar-value ui-corner-left ui-corner-right" style="width: 100%; height:22px; margin-top: 20px;"></div>
</div>
<div id="error-modal" title="Error" style="display: none;">
    Возникли проблемы при подготовке файла, повторите попытку
</div>