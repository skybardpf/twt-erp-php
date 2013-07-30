<?php
/**
 * Корзина акционирования.
 *
 * @author Burtsev R.V. <roman@artektiv.ru>
 *
 * @var $this   Corporatization_basketController
 */
?>

<?php
Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/arbor.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/arbor-graphics.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/corporatization_basket/basket.js');
?>
<h2>Корзина акционирования юридического лица</h2>
<canvas id="viewport" width="800" height="600"></canvas>
<script type="text/javascript">
var raw_data = <?php echo $basketObject; ?>
</script>