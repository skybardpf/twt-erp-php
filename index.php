<?php

// change the following paths if necessary

$yii    = dirname(__FILE__).'/../../yii/framework/yii.php';
$config = dirname(__FILE__).'/protected/config/main.php';

if ($_SERVER['HTTP_HOST'] == 'twt.local') {
	$yii = 'yii-1.1.13/yii.php';
	defined('YII_DEBUG') or define('YII_DEBUG',true);
} elseif ($_SERVER['HTTP_HOST'] == 'yury.twt-1c.ru') {
	$yii = dirname(__FILE__).'/../yii/framework/yii.php';
	defined('YII_DEBUG') or define('YII_DEBUG',true);
} elseif ($_SERVER['HTTP_HOST'] == 'twt-erp.artektiv.ru') {
	$yii = 'framework/yii.php';
} else {
	defined('YII_DEBUG') or define('YII_DEBUG',true);
}

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


require_once($yii);
Yii::createWebApplication($config)->run();
