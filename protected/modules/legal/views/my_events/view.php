<?php
/**
 * Просмотр события (мероприятия).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var My_eventsController | Calendar_eventsController $this
 * @var Event           $model
 * @var Organization    $organization
 */

Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/jquery.fileDownload.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/manage_files.js');
?>

<script>
    window.controller_name = '<?= $this->getId(); ?>';
</script>

<h2>Событие "<?= CHtml::decode($model->name); ?>"</h2>

<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/show_manage_files.js');

    if ($this instanceof Calendar_eventsController){
        $url_redirect = $this->createUrl('list', array("org_id" => $organization->primaryKey, "id" => $model->primaryKey));
        $url_delete = $this->createUrl('delete', array("org_id" => $organization->primaryKey, "id" => $model->primaryKey));
        $url_edit = $this->createUrl('edit', array("org_id" => $organization->primaryKey, "id" => $model->primaryKey));
    } else {
        $url_redirect = $this->createUrl('index');
        $url_delete = $this->createUrl('delete', array('id' => $model->primaryKey));
        $url_edit = $this->createUrl('edit', array('id' => $model->primaryKey));
    }

    if ($model->made_by_user){
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'link',
            'type'       => 'success',
            'label'      => 'Редактировать',
            'url'        => $url_edit
        ));

        if (!$model->deleted) {

            echo "&nbsp;";
            Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');

            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'    => 'submit',
                'type'          => 'danger',
                'label'         => 'Удалить',
                'htmlOptions'   => array(
                    'data-question'     => 'Вы уверены, что хотите удалить данное событие?',
                    'data-title'        => 'Удаление события',
                    'data-url'          => $url_delete,
                    'data-redirect_url' => $url_redirect,
                    'data-delete_item_element' => '1'
                )
            ));
        }
        echo '<br/><br/>';
    }
?>

<div>
<?php
    $div = '';
    if ($model->for_yur){
        $label = 'Для юридических лиц';
        $organizations = Organization::model()->getListNames($model->getForceCached());
        $contractors = Contractor::model()->getListNames($model->getForceCached());
        foreach ($model->list_yur as $list){
            if ($list['type_yur'] == 'Организации'){
                if (isset($organizations[$list['id_yur']])){
                    $div .= CHtml::link(
                        $organizations[$list['id_yur']],
                        $this->createUrl('organization/view', array('id' => $list['id_yur']))
                    ).'<br/>';
                }
            } elseif($list[$type] == 'Контрагенты'){
                if (isset($contractors[$list['id_yur']])){
                    $div .= CHtml::link(
                        $contractors[$list['id_yur']],
                        $this->createUrl('contractor/view', array('id' => $list['id_yur']))
                    ).'<br/>';
                }
            }
        }
    } else {
        $label = 'Для юрисдикций';
        $countries = Countries::model()->getDataNames($model->getForceCached());
        foreach ($model->countries as $country){
            $div .= ((isset($countries[$country])) ? $countries[$country] : '---').'<br/>';
        }
    }

    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes'=>array(
            array(
                'name' => 'div_list_yur',
                'label' => $label,
                'type' => 'raw',
                'value' => $div
            ),
            array(
                'name' => 'event_date',
                'label' => 'Первая дата наступления'
            ),
            array(
                'name' => 'notification_date',
                'label' => 'Первая дата напоминания'
            ),
            array(
                'name' => 'period',
                'label' => 'Периодичность'
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
    ?>
</fieldset>

<div id="preparing-file-modal" title="Подготовка файла..." style="display: none;">
    Подготавливается файл для скачивания, подождите...

    <div class="ui-progressbar-value ui-corner-left ui-corner-right" style="width: 100%; height:22px; margin-top: 20px;"></div>
</div>
<div id="error-modal" title="Error" style="display: none;">
    Возникли проблемы при подготовке файла, повторите попытку
</div>