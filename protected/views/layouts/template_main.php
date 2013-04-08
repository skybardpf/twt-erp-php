<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Мишенько
 * Date: 08.04.13
 * Time: 10:57
 * To change this template use File | Settings | File Templates.
 */

/*@var $this Controller */

Yii::app()->clientScript->registerCssFile(CHtml::asset(Yii::app()->basePath.'/../static/css/main.css'));

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
            $this->renderPartial('head');
        ?>
    </div>
    <div class="container">
        <?=$content?>
    </div>
</body>
</html>