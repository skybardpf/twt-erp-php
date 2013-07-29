<?php
/**
 * Список Юр.Лиц
 *
 * User: Forgon
 * Date: 23.04.2013 от рождества Христова
 *
 * @var $this   My_OrganizationsController
 * @var $models Organization[]
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
<h2>Мои юридические лица</h2>

<?php
    $countries = Countries::getValues();
    foreach($models as $k=>$v){
        $models[$k]->country = (isset($countries[$v->country]) ? $countries[$v->country] : '---');
    }
	$data = new CArrayDataProvider($models);
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $data,
        'template' => "{items}{pager}",
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
                'name' => 'creation_date',
                'header' => 'Дата добавления'
            ),
            array(
                'name' => 'creator',
                'header' => 'Пользователь, добавивший в систему'
            ),
        ),
    ));
?>