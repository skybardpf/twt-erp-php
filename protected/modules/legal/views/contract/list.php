<?php
/**
 *  Договоры
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var ContractController $this
 * @var Contract[]          $data
 * @var Organizations       $organization
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Новый Договор',
            'type'  => 'success',
            'size'  => 'normal',
            'url'   => $this->createUrl('add', array('org_id' => $organization->primaryKey))
        ));
    ?>
</div>

<h2>Договоры</h2>

<?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => new CArrayDataProvider($data),
        'template' => "{items} {pager}",
        'columns' => array(
            array(
                'name' => 'xxx',
                'header' => 'Номер',
            ),
            array(
                'name' => 'name',
                'header' => 'Название',
                'type' => 'raw',
                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("contract/view", array("id" => $data["id"])))'
            ),
            array(
                'name' => 'xxx',
                'header' => 'Характер договора',
            ),
            array(
                'name' => 'xxx',
                'header' => 'Контрагент',
            ),
            array(
                'name' => 'xxx',
                'header' => 'Дата заключения',
            ),
            array(
                'name' => 'xxx',
                'header' => 'Дата окончания',
            ),
            array(
                'name' => 'xxx',
                'header' => 'Сумма',
            ),
        ),
    ));
?>
