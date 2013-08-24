<?php
/**
 * Список доверенностей для организаций и контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Power_attorney_contractorController      $this
 * @var PowerAttorneyForContractor[]             $data
 * @var Contractor                               $model
 */
?>

<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новая доверенность',
                'type' => 'success',
                'size' => 'normal',
                'url' => $this->createUrl("power_attorney_contractor/add", array('cid' => $model->primaryKey))
            )); ?>
        </div>
        <h3>Доверенности</h3>

        <?php
        $provider = new CArrayDataProvider($data);
        $persons = Individual::model()->getDataNames($model->getForceCached());
        foreach ($provider->rawData as $k=>$v){
            $provider->rawData[$k]['owner_name'] = (isset($persons[$v['id_lico']])) ? $persons[$v['id_lico']] : NULL;
        }

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type' => 'striped bordered condensed',
            'dataProvider' => $provider,
            'template' => "{items}{pager}",
            'columns' => array(
                array(
                    'name'  => 'nom',
                    'header'=> 'Номер',
                ),
                array(
                    'name'   => 'name',
                    'type'  => 'raw',
                    'header' => 'Название',
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("power_attorney_contractor/view", array("id" => $data["id"])))'
                ),
                array(
                    'name'   => 'owner_name',
                    'header' => 'Кому выдана',
                    'type'   => 'raw',
                    'value'  => '(is_null($data["owner_name"])) ? "Не задано" : CHtml::link($data["owner_name"], Yii::app()->getController()->createUrl("Individual/view", array("id" => $data["id_lico"])))'
                ),
                array(
                    'name' => 'expire',
                    'header' => 'Срок действия'
                ),
            ),
        ));
        ?>
    </div>
</div>