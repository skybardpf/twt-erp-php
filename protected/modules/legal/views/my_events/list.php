<?php
/**
 * Календарь событий для определенной организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Calendar_eventsController   $this
 * @var Organizations               $organization
 * @var $data                       Event[]
 *
 */
?>
<h2>Ближайщие события</h2>

<?php
    echo CHtml::link('Ближайщие 10', '#') . ' | ' . CHtml::link('На год вперед', '#') . '<br/><br/>';

    $provider = new CArrayDataProvider($data);
    foreach($provider->rawData as $k=>$v){
        $provider->rawData[$k]['name'] = CHtml::link($v["name"], Yii::app()->getController()->createUrl("view", array("org_id" => $organization->primaryKey, "id" => $v["id"])));
    }

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $provider,
        'template' => "{items} {pager}",
        'columns' => array(
            array(
                'name' => 'name',
                'header' => 'Название',
                'type' => 'raw',
            ),
            array(
                'name' => 'event_date',
                'header' => 'Дата следующиего наступления'
            ),
        ),
    ));

