<?php
/**
 *  Документы -> Учредительные документы.
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 *
 *  @var $this          DocumentsController
 *  @var $docs          FoundingDocument[]
 *  @var $organization  Organization
 */
?>

<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новый учредительный документ',
                'type'  => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'  => 'normal', // null, 'large', 'small' or 'mini'
                'url'   => $this->createUrl("founding_documents/add", array('org_id' => $organization->primaryKey))
            )); ?>
        </div>
        <h3>Учредительные документы</h3>
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'         => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider($docs),
            'template'     => "{items}{pager}",
            'columns'      => array(
                array(
                    'name' => 'num',
                    'header' => 'Номер'
                ),
                array(
                    'header' => 'Название',
                    'type'   => 'raw',
                    'value'  => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("founding_document/view", array("id" => $data["id"])))'
                ),
                array('name' => 'expire', 'header' => 'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>