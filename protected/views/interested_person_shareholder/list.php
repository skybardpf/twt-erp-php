<?php
/**
 * Список заинтересованных лиц: Номинальные акционеры
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_personController $this
 * @var InterestedPersonShareholder[] $data
 * @var Organization $organization
 * @var array $history
 * @var string $last_date
 * @var string $type_person
 */

Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/interested_person/list.js');
?>
    <h3>Номинальные акционеры</h3>
    <div class="pull-right" style="margin-top: 15px;">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Новый акционер',
            'type' => 'success',
            'size' => 'normal',
            'url' => $this->createUrl(
                "interested_person_shareholder/add",
                array(
                    "org_id" => $organization->primaryKey,
                )
            )
        ));
        ?>
    </div>

<?php
echo CHtml::tag('div', array(), 'На ' . CHtml::encode($last_date));
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => new CArrayDataProvider($data),
    'template' => "{items} {pager}",
    'columns' => array(
        array(
            'name' => 'person_name',
            'type' => 'raw',
            'header' => 'Лицо'
        ),
        array(
            'name' => 'number_stake',
            'header' => 'Номер пакета акций',
        ),
        array(
            'name' => 'value_stake',
            'header' => '%, акций',
        ),
    )
));
?>
<fieldset class="scheduler-border">
    <legend class="scheduler-border">История изменений</legend>
    <?php
    echo CHtml::tag('div', array(
        'class' => 'org-info',
        'data-org-id' => $organization->primaryKey,
        'data-org-type' => MTypeOrganization::ORGANIZATION,
        'data-type-person' => $type_person,
    ));
    $history = array_reverse($history);
    foreach($history as $date){
        echo CHtml::link('с '.$date, '#', array(
            'data-date' => $date,
            'data-already-loaded' => 0,
            'data-open-toggle' => 0,
            'class' => 'history_date',
        )).'<br/>';
        echo CHtml::tag('div', array(
            'class' => 'block-history-'.$date
        ), '', true);
    }
    ?>
</fieldset>