<?php
return CMap::mergeArray(
    // наследуемся от main.php
    require(dirname(__FILE__) . '/main.php'),

    array(
        'components' => array(
//                'cache' => array('class'=>'system.caching.CDummyCache'),
            'cache' => array('class' => 'system.caching.CFileCache'),

            'soap' => array(
                'class' => 'SoapComponent',
                'wsdl' => 'http://144.76.90.163/twt_erp/ws/erp?wsdl',
                'connection_options' => array(
                    'login' => 'Site',
                    'password' => 'Site',
                )
            ),

            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                        'ipFilters' => array('127.0.0.1', '192.168.0.*'),
                    ),
                ),
            ),
        ),
    )
);