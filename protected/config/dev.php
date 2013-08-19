<?php
    return CMap::mergeArray(
        // наследуемся от main.php
        require(dirname(__FILE__).'/main.php'),

        array(
            'defaultController' => 'legal/organization',

            'components' => array(
//                'cache' => array('class'=>'system.caching.CDummyCache'),
                'cache' => array('class'=>'system.caching.CFileCache'),

                'soap' => array(
                    'class' => 'SoapComponent',
                    'wsdl' => 'http://144.76.90.162/twt_erp/ws/erp?wsdl',
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
                'uploadDocumentDir' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'filestorage'.DIRECTORY_SEPARATOR.'uploads',
                'uploadTmpDir' => '/tmp',
            ),
        )
    );