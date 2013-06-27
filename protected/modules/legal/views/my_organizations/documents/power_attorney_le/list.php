<?php
/**
 *  Документы -> Доверенности
 *  User: Skibardin A.A.
 *  Date: 26.06.13
 *
 *  @var $this       My_organizationsController
 *  @var $docs       PowerAttorneysLE[]
 */
?>

<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новая доверенность',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("power_attorney_le", array('action' => 'create', 'id' => $this->organization->primaryKey))
            )); ?>
        </div>
        <h3>Доверенности</h3>

        <?php
        $gridDataProvider = new CArrayDataProvider($docs);

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'      => 'striped bordered condensed',
            'dataProvider' => $gridDataProvider,
            'template'  => "{items}",
            'columns'   => array(
                array(
                    'name'   => 'id',
                    'header' => '#',
                    'type'   => 'raw',
                    'value'  => 'CHtml::link($data["id"], Yii::app()->getController()->createUrl("power_attorney_le", array("action" => "show", "id" => $data["id"])))'
                ),
                array(
                    'name'   => 'name',
                    'header' => 'Название',
//                    'type'   => 'raw',
//                    'value'  => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("document_show", array("id" => $data["id"])))'
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