<?php
/**
 * Вывод списка контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var $this   ContractorController
 * @var $data   Contractor[]
 */
?>
<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новый контрагент',
        'type'  => 'success',
        'size'  => 'normal',
        'url'   => $this->createUrl("add")
    )); ?>
</div>
<h2>Мои контрагенты</h2>

<?php
    $countries = Countries::getValues();

    /**
     * @var $data CArrayDataProvider
     */
    $data = new CArrayDataProvider($data);
    foreach($data->rawData as $k=>$m){
        $data->rawData[$k]['country'] = (isset($countries[$m['country']]) ? $countries[$m['country']] : '');
//        $data->rawData[$k]['parent'] = (isset($persons[$m['parent']]) ? $persons[$m['parent']] : '');
    }

    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $data,
        'template' => "{items} {pager}",
        'columns' => array(
            array(
                'name' => 'name',
                'header' => 'Название',
                'type' => 'raw',
                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("contractor/view", array("id" => $data["id"])))'
            ),
            array(
                'name' => 'country',
                'header' => 'Страна',
            ),
            array(
                'name' => 'creation_date',
                'header' => 'Дата добавления',
            ),
            array(
                'name' => 'creator',
                'header' => 'Пользователь, добавивший в систему',
            ),
        ),
    ));
?>