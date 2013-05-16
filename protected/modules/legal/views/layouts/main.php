<?php
/**
 * User: Мишенько
 * Date: 08.04.13
 * Time: 10:57
 *
 * @var $this Controller
 */

Yii::app()->clientScript->registerCssFile($this->asset_static.'/css/main.css');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css' );
Yii::app()->clientScript->registerScriptFile('easter_egg', $this->asset_static.'/js/common.js');
$this->widget('ext.widgets.loading.LoadingWidget');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=Yii::app()->language?>" lang="<?=Yii::app()->language?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="<?=Yii::app()->language?>" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body style="padding-top: 50px;">
    <div class="container">
        <?php
            $this->renderPartial('/layouts/head');
        ?>
    </div>
    <div class="container">
        <?=$content?>
    </div>
    <footer class="container">
        <hr>
        © TWT - Юридический блок, 2013
        <br><br>
    </footer>
</body>
</html>