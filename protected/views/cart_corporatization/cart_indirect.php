<?php
/**
 * Отображение корзины акционирования. Косвенная схема.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Cart_corporatizationController $this
 * @var array $data
 * @var Individual $individual
 */

$basketData = array();
$individuals = Individual::model()->listNames($this->getForceCached());
$name = (isset($individuals[$individual->primaryKey])) ? $individuals[$individual->primaryKey] : '';
$provider = new CArrayDataProvider($data);
foreach ($provider->rawData as $v) {
    $basketData[] = array(
        'id1' => $v->id_subject,
        'type1' => $v->id_subject,
        'title1' => $v->name_subject,

        'id2' => $individual->primaryKey,
        'type2' => 'Физические лица',
        'title2' => $name,

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
}$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $provider,
    'template' => "{items} {pager}",
    'columns' => array(
        array(
            'name' => 'name_subject',
            'header' => 'Объект владения',
        ),
        array(
            'name' => 'percent',
            'header' => 'Доля, %'
        ),
    ),
));