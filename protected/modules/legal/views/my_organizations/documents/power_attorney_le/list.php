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
                'url' => $this->createUrl("add_power_attorney_le", array('org_id' => $this->organization->primaryKey))
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
                    'type'  => 'raw',
                    'value' => 'CHtml::link($data["nom"], Yii::app()->getController()->createUrl("show_power_attorney_le", array("id" => $data["id"])))'
                ),
                array(
                    'name'   => 'name',
                    'header' => 'Название',
                ),
                array(
                    'name'   => 'id_yur',
//                    'header' => 'Название',
                ),
                array(
                    'name'   => 'owner_name',
                    'header' => 'Кому выдана',
                    'type'   => 'raw',
                    'value'  => '(is_null($data["owner_name"])) ? "Не задано" : CHtml::link($data["owner_name"], Yii::app()->getController()->createUrl("/legal/individuals/view/", array("id" => $data["id_lico"])))'
                ),
                array('name'=>'expire', 'header'=>'Срок действия'),
            ),
        ));
        ?>
    </div>
</div>