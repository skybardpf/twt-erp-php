<?php
return CMap::mergeArray(
    // наследуемся от main.php
    require(dirname(__FILE__) . '/main.php'),

    array(
        'components' => array(
            'cache' => array('class' => 'system.caching.CFileCache'),

            'soap' => array(
                'class' => 'SoapComponent',
//                'wsdl' => 'http://172.22.0.11/twt_erp/ws/erp?wsdl',
                'wsdl' => 'http://144.76.182.82/erp/ws/erp_jur?wsdl',
                'connection_options' => array(
                    'login' => 'Site',
                    'password' => 'Site',
                )
            ),
        ),
    )
);