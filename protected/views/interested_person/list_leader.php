<?php
/**
 * Список заинтересованных лиц: Руководители
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_personController $this
 * @var InterestedPersonLeader[] $data
 * @var Organization $organization
 * @var array $history
 * @var string $last_date
 */
?>
<h3>Руководители</h3>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
        <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новый руководитель',
                'type' => 'success',
                'size' => 'normal',
                'url' => $this->createUrl(
                    "interested_person_leader/add",
                    array(
                        "org_id" => $organization->primaryKey,
                    )
                )
            ));
        ?>
        </div>

        <?php
        $provider = new CArrayDataProvider($data);
        foreach ($provider->rawData as $k => $v) {
            $provider->rawData[$k]['person_name'] = CHtml::link(
                CHtml::encode($v['person_name']),
                $this->createUrl(
                    'interested_person_leader/view',
                    array(
                        'id' => $v['id'],
                        'type_lico' => $v['type_lico'],
                        'id_yur' => $organization->primaryKey,
                        'type_yur' => MTypeOrganization::ORGANIZATION,
                        'date' => $v['date'],
                    )
                )
            );
        }
        echo CHtml::tag('div', array(), 'На '.CHtml::encode($last_date));
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type' => 'striped bordered condensed',
            'dataProvider' => $provider,
            'template' => "{items} {pager}",
            'columns' => array(
                array(
                    'name' => 'person_name',
                    'type' => 'raw',
                    'header' => 'Лицо'
                ),
                array(
                    'name' => 'percent',
                    'header' => '%, акций',
                ),
            )
        ));
        ?>
    </div>
</div>