<?php
    /**
 * Вывод списка групп контрагентов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Contractor_groupController      $this
 * @var array                           $data
 */

    Yii::app()->clientScript->registerCssFile($this->asset_static.'/js/libs/extjs4/resources/css/ext-all.css');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/libs/extjs4/ext-all.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/contractor_group/index.js');
?>
<script>
    <?= 'window.global_data = ' . (empty($data) ? CJSON::encode(array()) : CJSON::encode($data)); ?>
</script>

<h2>Группы контрагентов</h2>

<div id="tree-contractor"></div>