<?php
/** @var $this Template_exampleController */
?>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новый учредительный документ',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("document_add")
            )); ?>
        </div>
        <h3>Учредительные документы</h3>

        <?php
        $gridDataProvider = new CArrayDataProvider(array(
            array('id'=>1, 'title'=>'документ 1', 'term'=>'20.33.23'),
            array('id'=>2, 'title'=>'документ 2', 'term'=>'01.12.23'),
            array('id'=>3, 'title'=>'документ 3', 'term'=>'21.23.09'),
        ));

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'=>$gridDataProvider,
            'template'=>"{items}",
            'columns'=>array(
                array('name'=>'id', 'header'=>'#'),
                array(
                    'name'=>'title',
                    'header'=>'Название',
                    'type' => 'raw',
                    'value' => 'CHtml::link($data["title"], Yii::app()->getController()->createUrl("document_show", array("id" => $data["id"])))'
                ),
                array('name'=>'term', 'header'=>'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новая доверенность',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("document_add")
            )); ?>
        </div>
        <h3>Доверенности</h3>

        <?php
        $gridDataProvider = new CArrayDataProvider(array(
            array('id'=>1, 'title'=>'документ 1', 'owner'=>'Окуджава Булат Шалвович', 'term'=>'20.33.23'),
            array('id'=>2, 'title'=>'документ 2', 'owner'=>'Окуджава Булат Шалвович', 'term'=>'01.12.23'),
            array('id'=>3, 'title'=>'документ 3', 'owner'=>'Навальный Алексей Анатольевич', 'term'=>'21.23.09'),
        ));

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'=>$gridDataProvider,
            'template'=>"{items}",
            'columns'=>array(
                array('name'=>'id', 'header'=>'#'),
                array(
                    'name'=>'title',
                    'header'=>'Название',
                    'type' => 'raw',
                    'value' => 'CHtml::link($data["title"], Yii::app()->getController()->createUrl("document_show", array("id" => $data["id"])))'
                ),
                array(
                    'name'=>'owner',
                    'header'=>'Кому выдана',
                    'type' => 'raw',
                    'value' => 'CHtml::link($data["owner"], Yii::app()->getController()->createUrl("person_show", array("id" => $data["id"])))'
                ),
                array('name'=>'term', 'header'=>'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новый свободный документ',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("document_add")
            )); ?>
        </div>
        <h3>Свободные документы</h3>

        <?php
        $gridDataProvider = new CArrayDataProvider(array(
            array('id'=>1, 'title'=>'документ 1', 'term'=>'20.33.23'),
            array('id'=>2, 'title'=>'документ 2', 'term'=>'01.12.23'),
            array('id'=>3, 'title'=>'документ 3', 'term'=>'21.23.09'),
        ));

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'=>$gridDataProvider,
            'template'=>"{items}",
            'columns'=>array(
                array('name'=>'id', 'header'=>'#'),
                array(
                    'name'=>'title',
                    'header'=>'Название',
                    'type' => 'raw',
                    'value' => 'CHtml::link($data["title"], Yii::app()->getController()->createUrl("document_show", array("id" => $data["id"])))'
                ),
                array('name'=>'term', 'header'=>'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>