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

<?php /* Доверенности */?>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новая доверенность',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("add_attorney", array('id' => $this->organization->primaryKey))
            )); ?>
        </div>
        <h3>Доверенности</h3>

        <?php
        $gridDataProvider = new CArrayDataProvider($PAdocs);

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'=>$gridDataProvider,
            'template'=>"{items}",
            'columns'=>array(
                /*
                 номер, название, кому выдана, срок действия
                */
	            array('name' => 'id', 'header' => '#'),
                array(
                    'name'   => 'name',
                    'header' => 'Название',
                    'type'   => 'raw',
                    'value'  => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("document_show", array("id" => $data["id"])))'
                ),
                /*array(
                    'name'   => 'owner',
                    'header' => 'Кому выдана',
                    'type'   => 'raw',
                    'value'  => 'CHtml::link($data["owner"], Yii::app()->getController()->createUrl("person_show", array("id" => $data["id"])))'
                ),*/
                //array('name'=>'term', 'header'=>'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>

<?php /* Свободные документы */?>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новый свободный документ',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("free_document", array("action"=> "create", "id" => $this->organization->primaryKey))
            )); ?>
        </div>
        <h3>Свободные документы</h3>

        <?php
        $gridDataProvider = new CArrayDataProvider($freeDocs);

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'=>$gridDataProvider,
            'template'=>"{items}",
            'columns'=>array(
                array('name'=>'id', 'header'=>'#'),
                array(
                    'name'  => 'name',
                    'header'=> 'Название',
                    'type'  => 'raw',
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("free_document", array("action"=> "show", "id" => $data["id"])))'
                ),
                array('name'=>'expire', 'header'=>'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>

<?php $this->endContent(); ?>