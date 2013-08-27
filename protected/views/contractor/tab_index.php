<?php
/**
 * Вывод списка контрагентов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractorController    $this
 * @var Contractor[]            $data
 * @var ContractorGroup[]       $groups
 */
?>
<div class="pull-right" style="margin-top: 15px;">
<?php
    Yii::app()->clientScript->registerCssFile($this->asset_static.'/js/ext4/resources/css/ext-all.css');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/ext4/ext-all.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/contractor/index.js');

    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новый контрагент',
        'type'  => 'success',
        'size'  => 'normal',
        'url'   => $this->createUrl("add")
    ));
?>
</div>
<script>
    <?= 'window.global_data = ' . (empty($data) ? CJSON::encode(array()) : CJSON::encode($data)); ?>
</script>

<h2>Контрагенты</h2>

<div id="tree-contractor"></div>