<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
Yii::setPathOfAlias('filestorage', dirname(__FILE__).'/../filestorage');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => 'TWT Consult',
//	'sourceLanguage' => 'root',

    'sourceLanguage'=>'en_US',
    'language'=>'ru',
    'charset'=>'utf-8',

	'preload' => array('log', 'bootstrap'),

	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.components.Enumerable.*',
		'application.components.Validators.*',
	),

    'defaultController' => 'organization',

    'modules'=>array(
		'legal',
        'calc' => array(),
//		'gii' => array(
//			'generatorPaths'=>array(
//				'bootstrap.gii',
//			),
//			'class'=>'system.gii.GiiModule',
//			'password'=>'1',
//			// If removed, Gii defaults to localhost only. Edit carefully to taste.
//			'ipFilters'=>array('127.0.0.1','::1', '192.168.0.*'),
//		),
	),

	// application components
	'components' => array(
		'user' => array(
			'allowAutoLogin' => true,
		),
		'calc' => array(
			'class' => 'SoapComponent',
			'wsdl'  => 'http://144.76.90.163/testmakarov/ws/CalcIns?wsdl',
			'connection_options' => array(
				'login'     => 'Site',
				'password'  => 'Site',
			)
		),
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		'urlManager' => array(
			'urlFormat'      => 'path',
			'showScriptName' => false
		),
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/twt.db',
			'schemaCachingDuration' => YII_DEBUG ? 10 : 3600,
			'enableParamLogging' => YII_DEBUG,
			'enableProfiling' => YII_DEBUG
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log' => array(
			'class'  => 'CLogRouter',
			'routes' => array(
				array(
					'class'  =>'CFileLogRoute',
					'levels' =>'error, warning',
				),
                array(
                    'class' => 'CFileLogRoute',
                    'categories' => 'soap',
                    'logFile' => 'soap_log.log'
                ),
//				array(
//					'class'     => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
//					'ipFilters' => array('127.0.0.1','192.168.0.*', '83.229.142.164'),
//				),
			),
		),
	),
	'params' => array(
		// this is used in contact page
		'adminEmail'=>'skybardpf@artektiv.ru',

        'uploadDocumentDir' => 'filestorage.twt-erp.uploads',
	),
);