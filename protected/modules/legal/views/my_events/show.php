<?php
/**
 * Просмотр события (мероприятия).
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

<h2>Событие "<?= CHtml::decode($model->name); ?>"</h2>

<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/show_manage_files.js');

    if ($model->made_by_user){
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'link',
            'type'       => 'success',
            'label'      => 'Редактировать',
            'url'        => $this->createUrl("edit", array('id' => $model->primaryKey))
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
                    'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
                    'data-redirect_url' => $this->createUrl('index'),
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
    $organizations = Organization::model()->getListNames();
    $contractors = Contractor::model()->getListNames();

    if ($model->for_yur){
        foreach ($model->list_yur as $list){
            for ($i = 0, $l=count($list)/2; $i<$l; $i++){
                $type = 'type_yur'.$i;
                $id = 'id_yur'.$i;
                if ($list[$type] == 'Организации'){
                    if (isset($organizations[$list[$id]])){
                        $div .= CHtml::link(
                                $organizations[$list[$id]],
                                $this->createUrl('my_organizations/view', array('id' => $list[$id]))
                            ).'<br/>';
                    }
                } elseif($list[$type] == 'Контрагенты'){
                    if (isset($contractors[$list[$id]])){
                        $div .= CHtml::link(
                                $contractors[$list[$id]],
                                $this->createUrl('contractor/view', array('id' => $list[$id]))
                            ).'<br/>';
                    }
                }
            }
        }
    }

    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes'=>array(
            array('name' => 'div_list_yur', 'label' => 'Для юридических лиц', 'type' => 'raw', 'value' => $div),
            array('name' => 'event_date', 'label' => 'Первая дата наступления'),
            array('name' => 'notification_date', 'label' => 'Первая дата напоминания'),
            array('name' => 'period', 'label' => 'Периодичность'),
        )
    ));
?>
</div>

<?php
$counts = UploadFile::getCountTypeFiles(UploadFile::CLIENT_ID, get_class($model), $model->primaryKey);
$div = '';
if (isset($counts['files']) && $counts['files']){
    $div .= CHtml::link('Скачать файлы', '#', array('class' => 'download_online')) . '<br/>';
}
if (!empty($div)){
    echo CHtml::tag('fieldset',
        array(
            'class' => 'links_for_download',
            'data-id' => $model->primaryKey
        ),
        $div
    );
}
?>