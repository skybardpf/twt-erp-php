<?php
/**
 * Вывод списка шаблонов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Template_libraryController      $this
 * @var TemplateLibraryGroup[]          $groups
 */

$cs = Yii::app()->clientScript;
$cs->registerCssFile($this->asset_static.'/js/ext4/resources/css/ext-all.css');
$cs->registerScriptFile($this->asset_static.'/js/ext4/ext-all.js');
$cs->registerScriptFile($this->asset_static.'/js/template_library/index.js');
?>
<script>
    <?= 'window.global_data = ' . (empty($groups) ? CJSON::encode(array()) : CJSON::encode($groups)); ?>
</script>

<h2>Библиотека шаблонов</h2>

<div id="tree-template-library"></div>