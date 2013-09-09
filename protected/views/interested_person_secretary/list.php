<?php
/**
 * Список заинтересованных лиц: Секретари
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_personController $this
 * @var InterestedPersonSecretary[] $data
 * @var Organization $organization
 * @var array $history
 * @var string $last_date
 * @var string $type_person
 */

Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/interested_person/list.js');
?>
    <div class="pull-right" style="margin-top: 15px;">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Новый секретарь',
            'type' => 'success',
            'size' => 'normal',
            'url' => $this->createUrl(
                "interested_person_secretary/add",
                array(
                    "org_id" => $organization->primaryKey,
                )
            )
        ));
        ?>
    </div>
<h3>Секретари</h3>

<?php
echo CHtml::tag('div', array(), 'На ' . CHtml::encode($last_date));
echo $this->renderPartial('/interested_person_secretary/_list_grid_view', array(
    'data' => $data
), true);
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