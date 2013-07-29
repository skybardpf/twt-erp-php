<?php
/**
 * Календарь событий для определенной организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var $this           Calendar_eventsController
 * @var $organization   Organizations
 * @var $data           Event[]
 *
 */
?>
<h2>Ближайшие события</h2>

<?php
    echo CHtml::link('Ближайшие 10', '#') . ' | ' . CHtml::link('На год вперед', '#') . '<br/><br/>';

    $data = new CArrayDataProvider(array());

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $data,
        'template' => "{items} {pager}",
        'columns' => array(
            array(
                'name' => 'name',
                'header' => 'Название',
                'type' => 'raw',
                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("my_events/view", array("id" => $data["id"])))'
            ),
            array(
                'name' => 'event_date',
                'header' => 'Дата следующего наступления'
            ),
        ),
    ));

