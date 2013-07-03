<?php
/**
 * Список Юр.Лиц
 *
 * User: Forgon
 * Date: 23.04.2013 от рождества Христова
 *
 * @var $this   My_OrganizationsController
 * @var $models Organizations[]
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новое юридическое лицо',
        'type'  => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size'  => 'normal', // null, 'large', 'small' or 'mini'
        'url'   => $this->createUrl("add")
    )); ?>
</div>
<h2>Мои юридические лица</h2>

<?php
	$dataProvider = new CArrayDataProvider($models);

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'dataProvider'=>$dataProvider,
        'template'=>"{items}",
        'columns'=>array(
            array('name'=>'id', 'header'=>'#'),
            array(
                    'name'=>'name', 
                    'header'=>'Название', 
                    'type' => 'raw', 
                    'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("my_organizations/view", array("id" => $data["id"])))'
            ),
            /*array('name'=>'country', 'header'=>'Страна'),
            array('name'=>'inn_kpp', 'header'=>'ИНН/КПП')*/
        ),
    ));
?>