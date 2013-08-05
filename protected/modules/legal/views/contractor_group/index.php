<?php
    /**
 * Вывод списка групп контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Contractor_groupController      $this
 * @var array                           $data
 */

    Yii::app()->clientScript->registerCssFile($this->asset_static.'/js/ext4/resources/css/ext-all.css');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/ext4/ext-debug.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/contractor_group/index.js');
?>
<script>
    <?= 'window.global_data = ' . (empty($data) ? CJSON::encode(array()) : CJSON::encode($data)); ?>
</script>
<!--<div class="pull-right" style="margin-top: 15px;"></div>-->

<h2>Группы контрагентов</h2>

<div id="tree-contractor"></div>