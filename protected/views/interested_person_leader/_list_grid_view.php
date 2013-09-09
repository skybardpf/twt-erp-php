<?php
/**
 * Список заинтересованных лиц: Руководители
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_personController $this
 * @var InterestedPersonLeader[] $data
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
            'name' => 'date',
            'header' => 'Дата вступления в должность',
        ),
        array(
            'name' => 'job_title',
            'header' => 'Наименование должности',
        ),
    )
));