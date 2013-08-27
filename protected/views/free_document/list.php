<?php
/**
 * Документы -> Свободные документы
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var DocumentsController $this
 * @var FreeDocument[]      $docs
 * @var Organization        $organization
 */
?>

<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новый свободный документ',
                'type'  => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'  => 'normal', // null, 'large', 'small' or 'mini'
                'url'   => $this->createUrl("free_document/add", array("org_id" => $organization->primaryKey))
            )); ?>
        </div>
        <h3>Свободные документы</h3>

        <?php
        $data = new CArrayDataProvider($docs);

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'  => $data,
            'template'      => "{items}{pager}",
            'columns'       => array(
                array('name'=>'num', 'header'=>'Номер'),
                array(
                    'name'  => 'name',
                    'header'=> 'Название',
                    'type'  => 'raw',
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("free_document/view", array("id" => $data["id"])))'
                ),
                array('name'=>'expire', 'header'=>'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>