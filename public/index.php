<?php
$yii = 'yii.php';

/**
 * Продакшн находится здесь.
 */
if ($_SERVER['HTTP_HOST'] == 'twt-erp.twtconsult.ru') {
    defined('YII_DEBUG') or define('YII_DEBUG',false);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
    $config = dirname(__FILE__).'/../protected/config/web/production.php';

} elseif ($_SERVER['HTTP_HOST'] == 'twt-erp.artektiv.ru'){
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    $config = dirname(__FILE__).'/../protected/config/web/demo.php';

} else {
    ini_set('display_error', 1);
    ini_set('error_reporting', E_ALL);

    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    $config = dirname(__FILE__).'/../protected/config/web/dev.php';
}
require_once($yii);
Yii::createWebApplication($config)->run();