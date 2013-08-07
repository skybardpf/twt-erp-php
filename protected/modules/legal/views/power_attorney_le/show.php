<?php
/**
 *  Документы -> Просмотр доверенности.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 *
 *  @var $this          Power_attorney_leController
 *  @var $model         OrganizationPowerAttorney
 *  @var $organization  Organization
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
                'data-question'     => 'Вы уверены, что хотите удалить данный документ?',
                'data-title'        => 'Удаление документа',
                'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
                'data-redirect_url' => $this->createUrl('documents/list', array('org_id' => $organization->primaryKey)),
                'data-delete_item_element' => '1'
            )
        ));
    }
?>

<br/><br/>
<div>
	<?php
        $individuals = Individuals::getValues();
        if (!isset($individuals[$model->id_lico])){
            $p = 'Не задано';
        } else {
            $p = CHtml::link(
                $individuals[$model->id_lico],
                $this->createUrl('individuals/view', array('id' => $model->id_lico))
            );
        }
        $this->widget('bootstrap.widgets.TbDetailView', array(
            'data' => $model,
            'attributes'=>array(
                array('name' => 'id_lico', 'type' => 'raw', 'label' => 'На кого оформлена', 'value' => $p),
                array('name' => 'nom',          'label' => 'Номер'),
                array('name' => 'name',         'label' => 'Название'),
                array('name' => 'typ_doc',      'label' => 'Вид'),
                array('name' => 'date',         'label' => 'Дата начала действия'),
                array('name' => 'expire',       'label' => 'Срок действия'),
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