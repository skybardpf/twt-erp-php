<?php
    return CMap::mergeArray(
        // наследуемся от main.php
        require(dirname(__FILE__).'/main.php'),

        array(
            'components' => array(
                'cache' => array('class'=>'system.caching.CFileCache'),

                'soap' => array(
                    'class' => 'SoapComponent',
                    'wsdl' => 'http://172.22.0.11/twt_erp/ws/erp?wsdl',
                    'connection_options' => array(
                        'login'     => 'Site',
                        'password'  => 'Site',
                    )
                ),
            )
        )
    );