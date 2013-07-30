<?php
$yii = 'yii.php';

/**
 * Продакшн находится здесь.
 */
if ($_SERVER['HTTP_HOST'] == 'twt-erp.twtconsult.ru') {
    defined('YII_DEBUG') or define('YII_DEBUG',false);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',0);
    $config = dirname(__FILE__).'/../protected/config/production.php';

} elseif ($_SERVER['HTTP_HOST'] == 'twt-erp.artektiv.ru'){
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    $config = dirname(__FILE__).'/../protected/config/demo.php';

} else {
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    $config = dirname(__FILE__).'/../protected/config/dev.php';
}

require_once($yii);
Yii::createWebApplication($config)->run();