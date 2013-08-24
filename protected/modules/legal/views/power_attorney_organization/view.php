<?php
/**
 *  Организации -> Просмотр доверенности.
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 *  @var Power_attorney_organizationController  $this
 *  @var PowerAttorneyForOrganization           $model
 *  @var Organization                           $organization
 */
?>

<script>
    window.controller_name = '<?= $this->getId(); ?>';
</script>

<h2>Доверенность</h2>

<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/jquery.fileDownload.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/legal/manage_files.js');


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
        $Individual = Individual::model()->getDataNames($model->getForceCached());
        if (!isset($Individual[$model->id_lico])){
            $p = 'Не задано';
        } else {
            $p = CHtml::link(
                $Individual[$model->id_lico],
                $this->createUrl('Individual/view', array('id' => $model->id_lico))
            );
        }
        $type_of_contract = '';
        $contract_types = ContractType::model()->listNames($model->getForceCached());
        foreach($model->type_of_contract as $t){
            $type_of_contract .= (!isset($contract_types[$t])) ? '---' : ' - '.$contract_types[$t].'<br/>';
        }

        $this->widget('bootstrap.widgets.TbDetailView', array(
            'data' => $model,
            'attributes'=>array(
                array(
                    'name' => 'id_lico',
                    'type' => 'raw',
                    'label' =>
                    'На кого оформлена',
                    'value' => $p
                ),
                array(
                    'name' => 'nom',
                    'label' => 'Номер'
                ),
                array(
                    'name' => 'name',
                    'label' => 'Наименование'
                ),
                array(
                    'name' => 'typ_doc',
                    'label' => 'Вид'
                ),
                array(
                    'name' => 'type_of_contract',
                    'label' => 'Действительна для договоров',
                    'value' => $type_of_contract,
                    'type' => 'raw'
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
</fieldset>

<?= $this->renderPartial('/_files/download_hint', array(), true); ?>