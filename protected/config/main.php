<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'TWT Consult',
	'sourceLanguage' => 'root',
	'language' => 'ru',

	'preload'=>array('log', 'bootstrap'),

	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		'legal', 'support',
		'gii' => array(
			'generatorPaths'=>array(
				'bootstrap.gii',
			),
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1', '192.168.0.*'),
		),
	),

	// application components
	'components' => array(
		'user' => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
		),
		'calc' => array(
			'class' => 'SoapComponent',
			'wsdl'  => 'http://144.76.90.162/testmakarov/ws/CalcIns?wsdl',
			'connection_options' => array(
				'login'     => 'Site',
				'password'  => 'Site',
			)
		),
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		// uncomment the following to enable URLs in path-format

		'urlManager' => array(
			'urlFormat'      => 'path',
			'showScriptName' => false
			/*'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),*/
		),
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/twt.db',
			'schemaCachingDuration' => YII_DEBUG ? 10 : 3600,
			'enableParamLogging' => YII_DEBUG,
			'enableProfiling' => YII_DEBUG
		),
		'yexcel' => array(
			'class' => 'ext.yexcel.Yexcel'
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
				array(
					'class'     => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
					'ipFilters' => array('127.0.0.1','192.168.0.*', '83.229.142.164'),
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'yury@artektiv.ru',
	),
);