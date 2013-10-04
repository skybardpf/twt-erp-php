<?php
/**
 *  Договоры
 *
 *  @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractController $this
 * @var Contract[]          $data
 * @var Organization       $organization
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Новый договор',
            'type'  => 'success',
            'size'  => 'normal',
            'url'   => $this->createUrl('add', array('org_id' => $organization->primaryKey))
        ));
    ?>
</div>

<h2>Договоры</h2>

<?php
    $provider = new CArrayDataProvider($data);
    $contractorTypes = ContractType::model()->listNames($this->getForceCached());
    foreach ($provider->rawData as $key=>$raw){
        $provider->rawData[$key]['contract_type_id'] = (isset($contractorTypes[$raw['contract_type_id']])) ? $contractorTypes[$raw['contract_type_id']] : '---';
    }

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $provider,
        'template' => "{items} {pager}",
        'htmlOptions'=> array('style' => 'font-size:12px;'),
        'columns' => array(
            array(
                'name' => 'number',
                'header' => 'Номер',
            ),
            array(
                'name' => 'name',
                'header' => 'Название',
                'type' => 'raw',
                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("contract/view", array("id" => $data["id"])))'
            ),
            array(
                'name' => 'contract_type_id',
                'header' => 'Вид договора',
            ),
            array(
                'name' => 'le_id',
                'header' => 'Контрагент',
            ),
            array(
                'name' => 'date',
                'header' => 'Дата заключения',
            ),
            array(
                'name' => 'expire',
                'header' => 'Дата окончания',
            ),
            array(
                'name' => 'dogovor_summ',
                'header' => 'Сумма',
            ),
        ),
    ));
?>
