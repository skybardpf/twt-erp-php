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
                'name' => 'character',
                'header' => 'Характер договора',
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
