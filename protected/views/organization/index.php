<?php
/**
 * Список организаций.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var OrganizationController  $this
 * @var Organization[]          $data
 * @var bool                    $force_cache
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новая организация',
        'type'  => 'success',
        'size'  => 'normal',
        'url'   => $this->createUrl("add")
    )); ?>
</div>
<h2>Организации</h2>

<?php
    $countries = Country::model()->listNames($force_cache);
    foreach($data as $k=>$v){
        $data[$k]->country = (isset($countries[$v->country]) ? $countries[$v->country] : '---');
    }
	$provider = new CArrayDataProvider($data);

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $provider,
        'template' => "{items} {pager}",
        'columns' => array(
            array(
                'name' => 'name',
                'header' => 'Название',
                'type' => 'raw',
                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("organization/view", array("id" => $data["id"])))'
            ),
            array(
                'name' => 'country',
                'header' => 'Страна'
            ),
            array(
                'name' => 'creator',
                'header' => 'Пользователь, добавивший в систему'
            ),
        ),
    ));
?>