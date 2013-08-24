<?php
    /**
     * Банковские счета -> Список.
     * @author Skibardin A.A. <webprofi1983@gmail.com>
     *
     * @var Settlement_accountsController   $this
     * @var SettlementAccount[]             $data
     *
     * @see SettlementAccount
     */

    $this->breadcrumbs = array(
        'Банковские счета'
    );
    echo CHtml::tag('h2', array(), 'Банковские счета');


    $provider = new CArrayDataProvider($data);

    $cur = Currencies::getValues();
    $p   = Individual::getValues();
    $org = Organization::model()->getListNames();
    foreach ($provider->rawData as $k=>$v){
        $person = '';
        if (!empty($provider->rawData[$k]->managing_persons)){
            foreach ($provider->rawData[$k]->managing_persons as $pid){
                if (isset($p[$pid])){
                    $person .= CHtml::link($p[$pid], $this->createUrl('/legal/Individual/view/', array('id' => $pid)));
                } else {
                    $person .= $pid;
                }
                $person .= '<br/>';
            }
        }
        $provider->rawData[$k]['str_managing_persons'] = $person;
        $provider->rawData[$k]['cur_name'] = (isset($cur[$v['cur']])) ? $cur[$v['cur']] : NULL;
        $provider->rawData[$k]['yur_name'] = (isset($org[$v['id_yur']])) ? $org[$v['id_yur']] : '';
    }

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $provider,
        'template' => "{items} {pager}",
        'columns' => array(
            array(
                'name'  => 's_nom',
                'header'=> 'Номер',
                'type'  => 'raw',
                'value' => 'CHtml::link($data["s_nom"], Yii::app()->getController()->createUrl("/legal/settlement_accounts/view", array("id" => $data["id"])))'
            ),
            array(
                'name'  => 'yur_name',
                'header'=> 'Организация',
//                'value' => '$data["type_yur"]'
            ),
            array(
                'name'  => 'cur',
                'header'=> 'Валюта',
                'value' => '(is_null($data["cur_name"])) ? "Не задано" : $data["cur_name"]'
            ),
            array(
                'name'  => 'bank_name',
                'header'=> 'Банк'
            ),
            array(
                'name'  => 'str_managing_persons',
                'type'  => 'raw',
                'header'=> 'Управляющая персона',
            ),
        )
    ));