<?php
/**
 * Вывод списка моих событий (мероприятий).
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var My_eventsController $this
 * @var Event[]             $data
 * @var bool                $force_cache
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
    $organizations = Organization::model()->getListNames($force_cache);
    $contractors = Contractor::model()->getListNames($force_cache);

    $provider = new CArrayDataProvider($data);


    $for_yur = true;
    if ($for_yur){
        $label = 'Запланировано для юр. лиц';
        foreach($provider->rawData as $k=>$m){
            $div = '';
            foreach ($m->list_yur as $list){
                if ($list['id_yur'] == 'Организации'){
                    if (isset($organizations[$list['id_yur']])){
                        $div .= CHtml::link(
                                ' - ' . $organizations[$list['id_yur']],
                                $this->createUrl('organization/view', array('id' => $list['id_yur']))
                            ).'<br/>';
                    }
                } elseif ($list['id_yur'] == 'Контрагенты'){
                    if (isset($organizations[$list['id_yur']])){
                        $div .= CHtml::link(
                                ' - ' . $organizations[$list['id_yur']],
                                $this->createUrl('contractor/view', array('id' => $list['id_yur']))
                            ).'<br/>';
                    }
                }

            }
            $provider->rawData[$k]['div_list_yur'] = $div;
        }

    } else {
        $label = 'Запланировано для стран';
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
                'header' => $label
            ),
        ),
    ));
?>