<?php
/** @var $this Template_exampleController */
?>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новое заинтересованное лицо',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("benefit_add")
            )); ?>
        </div>
        <h3>Заинтересованные лица</h3>

        <?php
        $gridDataProvider = new CArrayDataProvider(array(
            array('id'=>$id, 'name'=>'Малхасян Геворк Рубенович', 'shareholding'=>'33%'),
            array('id'=>$id, 'name'=>'Померанцев Павел Вячеславович', 'shareholding'=>'33%'),
            array('id'=>$id, 'name'=>'Крузенштерн Иван Фёдорович', 'shareholding'=>'80%'),
        ));

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'=>$gridDataProvider,
            'template'=>"{items}",
            'columns'=>array(
                array(
                    'name'=>'title',
                    'header'=>'Имя',
                    'type' => 'raw',
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("benefit_show", array("id" => $data["id"])))'
                ),
                array('name'=>'shareholding', 'header'=>'Величина пакета акций'),
            ),
        ));
        ?>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новый бенефициар',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => Yii::app()->getController()->createUrl("benefit_add")
            )); ?>
        </div>
        <h3>Бенефициары</h3>

        <?php
        $gridDataProvider = new CArrayDataProvider(array(
            array('id'=>$id, 'name'=>'Померанцев Павел Вячеславович', 'shareholding'=>'100%'),
            array('id'=>$id, 'name'=>'Крузенштерн Иван Фёдорович', 'shareholding'=>'46%'),
        ));

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'=>$gridDataProvider,
            'template'=>"{items}",
            'columns'=>array(
                array(
                    'name'=>'title',
                    'header'=>'Имя',
                    'type' => 'raw',
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("benefit_show", array("id" => $data["id"])))'
                ),
                array('name'=>'shareholding', 'header'=>'Величина пакета акций'),
            ),
        ));
        ?>
    </div>
</div>