<?php
/**
 *  Список доверенностей для организаций и контрагентов.
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 *  @var DocumentsController | Power_attorney_contractorController      $this
 *  @var PowerAttorneyForOrganization[]     $data
 *  @var Organization                       $organization
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
        $provider = new CArrayDataProvider($data);
        $p = Individuals::model()->getDataNames($organization->getForceCached());
        foreach ($provider->rawData as $k=>$v){
            $provider->rawData[$k]['owner_name'] = (isset($p[$v['id_lico']])) ? $p[$v['id_lico']] : NULL;
        }

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'      => 'striped bordered condensed',
            'dataProvider' => $provider,
            'template'  => "{items}{pager}",
            'columns'   => array(
                array(
                    'name'  => 'nom',
                    'header'=> 'Номер',
                ),
                array(
                    'name'   => 'name',
                    'type'  => 'raw',
                    'header' => 'Название',
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("power_attorney_organization/view", array("id" => $data["id"])))'
                ),
                array(
                    'name'   => 'owner_name',
                    'header' => 'Кому выдана',
                    'type'   => 'raw',
                    'value'  => '(is_null($data["owner_name"])) ? "Не задано" : CHtml::link($data["owner_name"], Yii::app()->getController()->createUrl("individuals/view", array("id" => $data["id_lico"])))'
                ),
                array(
                    'name'=>'expire',
                    'header'=>'Срок действия'
                ),
            ),
        ));
        ?>
    </div>
</div>