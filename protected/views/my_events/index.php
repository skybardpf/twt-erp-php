<?php
/**
 * Вывод списка моих событий (мероприятий).
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var My_eventsController $this
 * @var Event[]             $data
 * @var bool                $force_cache
 * @var int                 $for_yur
 * @var string              $country_id
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
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/my_events/index.js');

    $checked_0 = '';
    $checked_1 = '';
    if ($for_yur == 1){
        $checked_0 = 'checked="checked"';
    } else {
        $checked_1 = 'checked="checked"';
    }
?>
    <div class="control-group ">
<!--        <label class="control-label" for="EventForm_for_organization">Фильтр</label>-->
        <div class="controls">
            <input id="ytEventForm_for_organization" type="hidden" value="" name="EventForm[for_organization]">
            <label class="radio inline">
                <input id="EventForm_for_organization_0" value="1" <?= $checked_0; ?> type="radio" name="EventForm[for_organization]">
                <label for="EventForm_for_organization_0">По организациям</label>
            </label>
            <label class="radio inline">
                <input id="EventForm_for_organization_1" value="2" <?= $checked_1; ?> type="radio" name="EventForm[for_organization]">
                <label for="EventForm_for_organization_1">По странам</label>
            </label>
            <span class="help-inline error" id="EventForm_for_organization_em_" style="display: none"></span>
        </div>
    </div>
<?php
    $countries = Country::model()->listNames($force_cache);
    $countries[''] = '--- Все ---';
    echo CHtml::tag('div',
        array(
            'class' => 'block_countries' . ($for_yur == 1 ? ' hide' : '')
        ),
        Chtml::dropDownList('EventForm_countries', $country_id, $countries)
    );

    /**
     * Заполнение грида
     */
    $provider = new CArrayDataProvider($data);
    if ($for_yur == 1){
        $organizations = Organization::model()->getListNames($force_cache);
        $contractors = Contractor::model()->getListNames($force_cache);

        $label = 'Запланировано для юр. лиц';
        foreach($provider->rawData as $k=>$m){
            $div = '';
            foreach ($m->list_yur as $list){
                if ($list['type_yur'] == 'Организации'){
                    if (isset($organizations[$list['id_yur']])){
                        $div .= CHtml::link(
                                ' - ' . $organizations[$list['id_yur']],
                                $this->createUrl('organization/view', array('id' => $list['id_yur']))
                            ).'<br/>';
                    }
                } elseif ($list['type_yur'] == 'Контрагенты'){
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
        foreach($provider->rawData as $k=>$m){
            $div = '';
            foreach ($m->list_countries as $cid){
                if (isset($countries[$cid])){
                    $div .= ' - '.$countries[$cid].'<br/>';
                }

            }
            $provider->rawData[$k]['div_list_yur'] = $div;
        }
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