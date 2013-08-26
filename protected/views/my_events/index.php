<?php
/**
 * Вывод списка моих событий (мероприятий).
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var My_eventsController $this
 * @var Event[]             $data
 * @var EventForm           $model
 * @var bool                $force_cache
 * @var bool                $for_yur
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

    /* @var $form MTbActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id'    => 'form-my-events',
        'type'  => 'horizontal',
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnChange' => true,
        ),

    ));
    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
    }
    echo $form->radioButtonListInlineRow($model, 'for_organization', array(
        1 => 'По организациям',
        2 => 'По странам'
    ));
    $countries = Country::model()->listNames($force_cache);
    $countries[''] = '--- Все ---';
    echo CHtml::tag('div',
        array(
            'class' => 'block_countries' . ($model->for_organization == 1 ? ' hide' : '')
        ),
        $form->dropDownListRow($model, 'country_id', $countries)
    );
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'submit',
        'type'      => 'primary',
        'label'     => 'Фильтр'
    ));
    $this->endWidget();

    /**
     * Заполнение грида
     */
    $provider = new CArrayDataProvider($data,
        array(
        'pagination' => array(
            'pageSize' => 10000,
        ),
    ));
    if ($for_yur){
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