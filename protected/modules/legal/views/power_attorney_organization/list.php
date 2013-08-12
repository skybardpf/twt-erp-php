<?php
/**
 *  Список доверенностей для организаций и контрагентов.
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 *  @var DocumentsController | Power_attorney_contractorController      $this
 *  @var PowerAttorneyAbstract[]                                        $data
 *  @var $organization  Organization
 */
?>

<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Новая доверенность',
                'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size'=>'normal', // null, 'large', 'small' or 'mini'
                'url' => $this->createUrl("power_attorney_le/add", array('org_id' => $organization->primaryKey))
            )); ?>
        </div>
        <h3>Доверенности</h3>

        <?php
        $data = new CArrayDataProvider($docs);

        $p = Individuals::getValues();
        foreach ($data->rawData as $k=>$v){
            $data->rawData[$k]['owner_name'] = (isset($p[$v['id_lico']])) ? $p[$v['id_lico']] : NULL;
        }

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'      => 'striped bordered condensed',
            'dataProvider' => $data,
            'template'  => "{items}{pager}",
            'columns'   => array(
                array(
                    'name'  => 'nom',
                    'header'=> 'Номер',
//                    'type'  => 'raw',
//                    'value' => 'CHtml::link($data["nom"], Yii::app()->getController()->createUrl("show_power_attorney_le", array("id" => $data["id"])))'
                ),
                array(
                    'name'   => 'name',
                    'type'  => 'raw',
                    'header' => 'Название',
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("power_attorney_le/view", array("id" => $data["id"])))'
                ),
//                array(
//                    'name'   => 'id_yur',
////                    'header' => 'Название',
//                ),
                array(
                    'name'   => 'owner_name',
                    'header' => 'Кому выдана',
                    'type'   => 'raw',
                    'value'  => '(is_null($data["owner_name"])) ? "Не задано" : CHtml::link($data["owner_name"], Yii::app()->getController()->createUrl("individuals/view", array("id" => $data["id_lico"])))'
                ),
                array('name'=>'expire', 'header'=>'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>