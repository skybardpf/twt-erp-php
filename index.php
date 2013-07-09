<?php
$yii = 'yii.php';

if ($_SERVER['HTTP_HOST'] == 'twt-1c') {
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    $config = dirname(__FILE__).'/protected/config/dev.php';
} else {
    defined('YII_DEBUG') or define('YII_DEBUG',false);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
    $config = dirname(__FILE__).'/protected/config/production.php';
}

require_once($yii);
Yii::createWebApplication($config)->run();
