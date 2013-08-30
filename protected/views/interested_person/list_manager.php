<?php
/**
 * Список заинтересованных лиц: Менеджеры
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_personController $this
 * @var InterestedPerson[]  $data
 * @var Organization $organization
 */
?>
<h3>Менеджеры</h3>
<div class="row-fluid">
    <div class="span12">
        <div class="pull-right" style="margin-top: 15px;">
        <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Новый менеджер',
                'type' => 'success',
                'size' => 'normal',
                'url' => $this->createUrl(
                    "add",
                    array(
                        "org_id" => $organization->primaryKey,
                        "type" => MViewInterestedPerson::MANAGER,
                    )
                )
            ));
        ?>
        </div>

        <?php
//            $provider = (isset($data[InterestedPerson::ROLE_SHAREHOLDER])) ? $data[InterestedPerson::ROLE_SHAREHOLDER] : array();
//            $provider = new CArrayDataProvider($provider);
//            if (!empty($provider)){
//                foreach($provider->rawData as $k=>$v){
//                    $provider->rawData[$k]['yur_url'] = CHtml::link(
//                        $v['lico'],
//                        $this->createUrl(
//                            'view',
//                            array(
//                                'id' => $v['id'],
//                                'id_yur' => $v['id_yur'],
//                                'role' => $v['role'],
//                            )
//                        )
//                    );
//                    $provider->rawData[$k]['nominal'] = $v["nominal"] . " " . $v["currency"];
//                }
//            }
//            $this->widget('bootstrap.widgets.TbGridView', array(
//                'type'=>'striped bordered condensed',
//                'dataProvider'  => $provider,
//                'template'      => "{items} {pager}",
//                'columns'       => array(
//                    array(
//                        'name' => 'yur_url',
//                        'type' => 'raw',
//                        'header' => 'Лицо'
//                    ),
//                    array(
//                        'name' => 'nominal',
//                        'header' => 'Номинал акции',
//                    ),
//                    array(
//                        'name' => 'quantStock',
//                        'header' => 'Кол-во акций',
//                    ),
//                )
//            ));
        ?>
    </div>
</div>