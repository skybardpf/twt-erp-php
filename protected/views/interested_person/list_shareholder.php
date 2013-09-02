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
 */
?>
<h3>Номинальные акционеры</h3>
<div class="row-fluid">
    <div class="span12">
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
        echo 'На '.CHtml::encode($last_date);

        $provider = new CArrayDataProvider($data);
//        foreach ($provider->rawData as $k => $v) {
//            $provider->rawData[$k]['yur_url'] = CHtml::link(
//                $v['lico'],
//                $this->createUrl(
//                    'view',
//                    array(
//                        'id' => $v['id'],
//                        'id_yur' => $v['id_yur'],
//                        'role' => $v['role'],
//                    )
//                )
//            );
//            $provider->rawData[$k]['nominal'] = $v["nominal"] . " " . $v["currency"];
//        }
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
<!--
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php
/*            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новый бенефициар',
                'type' => 'success',
                'size' => 'normal',
                'url' => $this->createUrl(
                    "add",
                    array(
                        "id_yur" => $organization->primaryKey,
                        "role" => InterestedPerson::ROLE_BENEFICIARY,
                    )
                )
            ));
            */?>
        </div>
        <h3>Бенефициар</h3>
        <?php
/*        $provider = (isset($data[InterestedPerson::ROLE_BENEFICIARY])) ? $data[InterestedPerson::ROLE_BENEFICIARY] : array();
        $provider = new CArrayDataProvider($provider);
        if (!empty($provider)){
            foreach($provider->rawData as $k=>$v){
                $provider->rawData[$k]['yur_url'] = CHtml::link(
                    $v['lico'],
                    $this->createUrl(
                        'view',
                        array(
                            'id' => $v['id'],
                            'id_yur' => $v['id_yur'],
                            'role' => InterestedPerson::ROLE_BENEFICIARY,
                            'numPack' => $v['numPack'],
                        )
                    )
                );
                $provider->rawData[$k]['nominal'] = $v["nominal"] . " " . $v["currency"];
            }
        }
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'  => $provider,
            'template'      => "{items} {pager}",
            'columns'       => array(
                array(
                    'name' => 'yur_url',
                    'type' => 'raw',
                    'header' => 'Лицо'
                ),
                array(
                    'name' => 'nominal',
                    'header' => 'Номинал акции',
                ),
                array(
                    'name' => 'quantStock',
                    'header' => 'Кол-во акций',
                ),
            )
        ));
        */?>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php
/*            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новый директор',
                'type' => 'success',
                'size' => 'normal',
                'url' => $this->createUrl(
                    "add",
                    array(
                        "id_yur" => $organization->primaryKey,
                        "role" => InterestedPerson::ROLE_LEADER,
                    )
                )
            ));
            */?>
        </div>
        <h3>Директор</h3>
        <?php
/*        $provider = (isset($data[InterestedPerson::ROLE_LEADER])) ? $data[InterestedPerson::ROLE_LEADER] : array();
        $provider = new CArrayDataProvider($provider);
        if (!empty($provider)){
            foreach($provider->rawData as $k=>$v){
                $provider->rawData[$k]['yur_url'] = CHtml::link(
                    $v['lico'],
                    $this->createUrl(
                        'view',
                        array(
                            'id' => $v['id'],
                            'id_yur' => $v['id_yur'],
                            'role' => $v['role'],
                        )
                    )
                );
            }
        }
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider' => $provider,
            'template' => "{items} {pager}",
            'columns' => array(
                array(
                    'name' => 'yur_url',
                    'type' => 'raw',
                    'header' => 'Лицо'
                ),
                array(
                    'name' => 'date',
                    'header' => 'Дата вступления в должность',
                ),
                array(
                    'name' => 'job_title',
                    'header' => 'Наименование должности',
                ),
            )
        ));
        */?>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php
/*            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новый секретарь',
                'type' => 'success',
                'size' => 'normal',
                'url' => $this->createUrl(
                    "add",
                    array(
                        "id_yur" => $organization->primaryKey,
                        "role" => InterestedPerson::ROLE_SECRETARY,
                    )
                )
            ));
            */?>
        </div>
        <h3>Секретари</h3>
        <?php
/*        $provider = (isset($data[InterestedPerson::ROLE_SECRETARY])) ? $data[InterestedPerson::ROLE_SECRETARY] : array();
        $provider = new CArrayDataProvider($provider);
        if (!empty($provider)){
            foreach($provider->rawData as $k=>$v){
                $provider->rawData[$k]['yur_url'] = CHtml::link(
                    $v['lico'],
                    $this->createUrl(
                        'view',
                        array(
                            'id' => $v['id'],
                            'id_yur' => $v['id_yur'],
                            'role' => $v['role'],
                        )
                    )
                );
            }
        }
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'  => $provider,
            'template'      => "{items} {pager}",
            'columns'       => array(
                array(
                    'name' => 'yur_url',
                    'type' => 'raw',
                    'header' => 'Лицо'
                ),
                array(
                    'name' => 'date',
                    'header' => 'Дата вступления в должность',
                ),
            )
        ));
        */?>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
            <?php
/*            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новый менеджер',
                'type' => 'success',
                'size' => 'normal',
                'url' => $this->createUrl(
                    "add",
                    array(
                        "id_yur" => $organization->primaryKey,
                        "role" => InterestedPerson::ROLE_MANAGER,
                    )
                )
            ));
            */?>
        </div>
        <h3>Менеджеры</h3>
        <?php
/*        $provider = (isset($data[InterestedPerson::ROLE_MANAGER])) ? $data[InterestedPerson::ROLE_MANAGER] : array();
        $provider = new CArrayDataProvider($provider);
        if (!empty($provider)){
            foreach($provider->rawData as $k=>$v){
                $provider->rawData[$k]['yur_url'] = CHtml::link(
                    $v['lico'],
                    $this->createUrl(
                        'view',
                        array(
                            'id' => $v['id'],
                            'id_yur' => $v['id_yur'],
                            'role' => $v['role'],
                        )
                    )
                );
            }
        }
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'=>'striped bordered condensed',
            'dataProvider'  => $provider,
            'template'      => "{items} {pager}",
            'columns'       => array(
                array(
                    'name' => 'yur_url',
                    'type' => 'raw',
                    'header' => 'Лицо'
                ),
                array(
                    'name' => 'date',
                    'header' => 'Дата вступления в должность',
                ),
            )
        ));
        */?>
    </div>
</div>-->