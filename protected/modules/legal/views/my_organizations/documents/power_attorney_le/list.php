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
        $data = new CArrayDataProvider($docs);

//        var_dump($data->rawData);die;
        $p = Individuals::getValues();

//        $s = (is_null($data["owner_name"])) ? "Не задано" : CHtml::link($data["owner_name"], Yii::app()->getController()->createUrl("/legal/individuals/view/", array("id" => $data["id_lico"])));

//        var
        foreach ($data->rawData as $k=>$v){
//            var_dump('key = ' . $k);
//            var_dump($k, $v);
            $data->rawData[$k]['owner_name'] = (isset($p[$v['id_lico']])) ? $p[$v['id_lico']] : NULL;
        }
//
//        die;

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'      => 'striped bordered condensed',
            'dataProvider' => $data,
            'template'  => "{items}",
            'columns'   => array(
                array(
                    'name'  => 'nom',
                    'header'=> 'Номер',
                    'value' => '$data["nom"]'
                ),
//                array(
//                    'name'   => 'id',
//                    'header' => '#',
//                    'type'   => 'raw',
//                    'value'  => 'CHtml::link($data["id"], Yii::app()->getController()->createUrl("power_attorney_le", array("action" => "show", "id" => $data["id"])))'
//                ),
                array(
                    'name'   => 'name',
                    'header' => 'Название',
                    'type'   => 'raw',
                    'value'  => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("power_attorney_le", array("action" => "show", "id" => $data["id"])))'
                ),
                array(
                    'name'   => 'owner_name',
                    'header' => 'Кому выдана',
                    'type'   => 'raw',
//                    'value'  => '(isset($persons[$data["id_lico"]])) ? CHtml::link($persons[$data["id_lico"]], Yii::app()->getController()->createUrl("/legal/individuals/view/", array("id" => $data["id_lico"]))) : "Не задано"'
                    'value'  => '(is_null($data["owner_name"])) ? "Не задано" : CHtml::link($data["owner_name"], Yii::app()->getController()->createUrl("/legal/individuals/view/", array("id" => $data["id_lico"])))'

//                    'CHtml::link($data["owner"], Yii::app()->getController()->createUrl("person_show", array("id" => $data["id_lico"])))'
                ),
                array('name'=>'expire', 'header'=>'Срок действия'),
//                array('name'=>'nom', 'value' => '$data["nom"]'),
            ),
        ));
        ?>
    </div>
</div>