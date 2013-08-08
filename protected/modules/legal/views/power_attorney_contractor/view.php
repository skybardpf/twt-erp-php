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
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/show_manage_files.js');

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
    $persons = Individuals::model()->getDataNames($model->getForceCached());
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes'=>array(
            array(
                'name' => 'id_lico',
                'type' => 'raw',
                'label' => 'На кого оформлена',
                'value' => (isset($persons[$model->id_lico])) ? CHtml::link($persons[$model->id_lico], $this->createUrl('individuals/view', array('id' => $model->id_lico))) : '---'
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
?>
</div>

<?php
    $counts = UploadFile::getCountTypeFiles(UploadFile::CLIENT_ID, get_class($model), $model->primaryKey);
    $div = '';
    if (isset($counts['files']) && $counts['files']){
        $div .= CHtml::link('Скачать электронную версию', '#', array('class' => 'download_online')) . '<br/>';
    }
    if (isset($counts['scans']) && $counts['scans']){
        $div .= CHtml::link('Скачать сканы', '#', array('class' => 'download_scans')) . '<br/>';
    }
    if (!empty($div)){
        echo CHtml::tag('fieldset',
            array(
                'class' => 'links_for_download',
                'data-id' => $model->primaryKey
            ),
            $div
        //        . '<br/>'
        //        . CHtml::link('Сгенерировать документ', '#', array('class' => 'download_generic_doc'))
        );
    }
?>