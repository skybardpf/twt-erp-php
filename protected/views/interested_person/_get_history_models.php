<?php
/**
 * Список заинтересованных лиц: Номинальные акционеры
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_personController $this
 * @var InterestedPersonAbstract[] $data
 * @var Organization $organization
 * @var string $type_person
 */
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