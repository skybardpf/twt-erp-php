<?php
/**
 * Отображение корзины акционирования. Прямая    схема.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Cart_corporatizationController $this
 * @var array $data
 */

$basketData = array();
$provider = new CArrayDataProvider($data);
foreach ($provider->rawData as $v) {
    $basketData[] = array(
        'id1' => $v->id_subject,
        'type1' => 'rectangle',
        'title1' => $v->name_subject,
        'color1' => ($v->type_subject == 'Физические лица') ? '#c0c0c0' : '#ff3030',

        'id2' => $v->id_object,
        'type2' => 'rectangle',
        'title2' => $v->name_object,
        'color2' => ($v->type_object == 'Физические лица') ? '#c0c0c0' : '#ff3030',

        'percent' => $v->percent,
    );
}
if (!empty($basketData)) {
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/libs/arbor/arbor.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/libs/arbor/arbor-graphics.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/cart_corporatization/cart.js');
    $basketData = CJSON::encode($basketData);
    ?>
    <canvas id="viewport" height="600"></canvas>
    <script type="text/javascript">
        var raw_data = <?= $basketData; ?>;
    </script>
    <?php
}
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $provider,
    'template' => "{items} {pager}",
    'columns' => array(
        array(
            'name' => 'url_subject',
            'header' => 'Объект владения',
            'type' => 'raw',
        ),
        array(
            'name' => 'url_object',
            'header' => 'Владелец',
            'type' => 'raw',
        ),
        array(
            'name' => 'percent',
            'header' => 'Доля, %'
        ),
    ),
));