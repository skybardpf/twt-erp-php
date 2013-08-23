<?php
//    $fileStorageDir = dirname(__FILE__)
//        . DIRECTORY_SEPARATOR . '..'
//        . DIRECTORY_SEPARATOR . 'filestorage'
//        . DIRECTORY_SEPARATOR . 'twt-erp';
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
//            'params' => array(
//                'uploadDocumentDir' => $fileStorageDir.DIRECTORY_SEPARATOR.'uploads',
//                'fileStorageDir' => $fileStorageDir,
//            ),
        )
    );