<?php
/**
 * Список организаций.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var OrganizationController      $this
 * @var Organization[]              $data
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новое юридическое лицо',
        'type'  => 'success',
        'size'  => 'normal',
        'url'   => $this->createUrl("add")
    )); ?>
</div>
<h2>Организации</h2>

<?php
    $countries = Countries::getValues();
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
//            array(
//                'name' => 'creation_date',
//                'header' => 'Дата добавления'
//            ),
            array(
                'name' => 'creator',
                'header' => 'Пользователь, добавивший в систему'
            ),
        ),
    ));
?>