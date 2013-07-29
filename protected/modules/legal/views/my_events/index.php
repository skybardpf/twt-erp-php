<?php
/**
 * Вывод списка моих событий (мероприятий).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var My_eventsController $this
 * @var Event[]             $data
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новое событие',
        'type' => 'success',
        'size' => 'normal',
        'url' => $this->createUrl("add")
    )); ?>
</div>
<h2>Мои события</h2>

<?php
    $organizations = Organizations::getValues();
    $contractors = Contractor::getValues();

    $provider = new CArrayDataProvider($data);
    foreach($provider->rawData as $k=>$m){
        $div = '';

        if ($m->for_yur){
            foreach ($m->list_yur as $list){
                for ($i = 0, $l=count($list)/2; $i<$l; $i++){
                    $type = 'type_yur'.$i;
                    $id = 'id_yur'.$i;
                    if ($list[$type] == 'Организации'){
                        if (isset($organizations[$list[$id]])){
                            $div .= CHtml::link(
                                    ' - ' . $organizations[$list[$id]],
                                    $this->createUrl('my_organizations/view', array('id' => $list[$id]))
                                ).'<br/>';
                        }
                    } elseif($list[$type] == 'Контрагенты'){
                        if (isset($contractors[$list[$id]])){
                            $div .= CHtml::link(
                                    ' - ' . $contractors[$list[$id]],
                                    $this->createUrl('contractor/view', array('id' => $list[$id]))
                                ).'<br/>';
                        }
                    }
                }
            }
        }
        $provider->rawData[$k]['div_list_yur'] = $div;
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
                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("my_events/view", array("id" => $data["id"])))'
            ),
            array(
                'name' => 'event_date',
                'header' => 'Первая дата'
            ),
            array(
                'name' => 'period',
                'header' => 'Периодичность'
            ),
            array(
                'name' => 'div_list_yur',
                'type' => 'raw',
                'header' => 'Запланировано для юр. лиц'
            ),
        ),
    ));
?>