<?php
/**
 * @var $this       My_OrganizationsController  Контролер
 * @var $Fdocs      FoundingDocument[]          Учредительные документы
 * @var $PAdocs     PowerAttorneysLE[]          Доверенности
 * @var $freeDocs   FreeDocument[]              Свободные документы
 */

$this->beginContent('/my_organizations/show'); ?>

<?php /* Учредительные документы */?>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новый учредительный документ',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("add_founding", array('id' => $this->organization->primaryKey))
            )); ?>
        </div>
        <h3>Учредительные документы</h3>
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'         => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider($Fdocs),
            'template'     => "{items}",
            'columns'      => array(
                array('name' => 'id', 'header' => 'Номер'),
                array(
                    'header' => 'Название',
                    'type'   => 'raw',
                    'value'  => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("show_founding", array("id" => $data["id"])))'
                ),
                //array('name' => 'term', 'header' => 'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>

<?php
    /* Доверенности */
    $this->renderPartial('documents/power_attorney_le/list', array(
        'docs' => $PAdocs
    ));
?>

<?php
    /* Свободные документы */
    $this->renderPartial('documents/free_document/list', array(
        'docs' => $freeDocs
    ));
?>


<?php $this->endContent(); ?>