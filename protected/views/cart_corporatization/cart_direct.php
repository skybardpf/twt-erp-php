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
        'type1' => $v->type_subject,
        'title1' => $v->name_subject,
        'id2' => $v->id_object,
        'type2' => $v->id_object,
        'title2' => $v->name_object,
        'percent' => $v->percent,
    );
}
if (!empty($basketData)) {
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/libs/arbor/arbor.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/libs/arbor/arbor-graphics.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/cart_corporatization/cart.js');
    $basketData = CJSON::encode($basketData);
    ?>
    <canvas id="viewport" width="800" height="600"></canvas>
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
            'name' => 'name_subject',
            'header' => 'Объект владения',
        ),
        array(
            'name' => 'name_object',
            'header' => 'Владелец'
        ),
        array(
            'name' => 'percent',
            'header' => 'Доля, %'
        ),
    ),
));