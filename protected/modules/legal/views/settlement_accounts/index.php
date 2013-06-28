<?php
/**
 *  Банковские счета -> Список.
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this      Settlement_accountsController
 *  @var $accounts  SettlementAccount[]
 */
?>

<?php
$this->breadcrumbs = array(
    'Банковские счета'
);
echo CHtml::tag('h2', array(), 'Банковские счета');

if (!$accounts) {
    echo 'Ни одного банковского счета не зарегистрировано.';
} else {
    $data = new CArrayDataProvider($accounts);

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',
        'dataProvider' => $data,
//        'template'=>"{items}",
        'columns'=>array(
            array(
                'name'  => 'id',
                'header'=> '#',
                'type'  => 'raw',
                'value' => 'CHtml::link($data["id"], Yii::app()->getController()->createUrl("/legal/my_organizations/settlement", array("action" => "show", "id" => $data["id"])))'
            ),
            array(
                'name'  => 'type_yur',
                'header'=> 'Тип юр. лица',
                'value' => '$data["type_yur"]'
            ),
            array(
                'name'  => 'name',
                'header'=> 'Название'
            ),
        ),
    ));
}
?>