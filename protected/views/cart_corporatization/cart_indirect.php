<?php
/**
 * Отображение корзины акционирования. Косвенная схема.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Cart_corporatizationController $this
 * @var array $data
 */

$provider = new CArrayDataProvider($data);

$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $provider,
    'template' => "{items} {pager}",
    'columns' => array(
        array(
            'name' => 'object',
            'header' => 'Объект владения',
        ),
        array(
            'name' => 'creator',
            'header' => 'Доля, %'
        ),
    ),
));