<?php
    return CMap::mergeArray(
        // наследуемся от main.php
        require(dirname(__FILE__).'/main.php'),

        array(
            'defaultController' => 'legal/organization',

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
            ),

            'params' => array(
                /**
                 * Директория для загрузки документов.
                 */
                'uploadDocumentDir' => dirname(__FILE__).'/../filestorage',
//                'uploadTmpDir' => '/tmp',
            ),
        )
    );