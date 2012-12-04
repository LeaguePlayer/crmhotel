<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Home-city Администраторская панель',
    'sourceLanguage' => 'ru',
   'language' => 'ru',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
         //  'application.modules.user.models.*',
//        'application.modules.user.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		// 'user',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
		//	'ipFilters'=>array(),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
                        // enable cookie-based authentication
                        'allowAutoLogin'=>true,
                        'loginUrl' => array('/users/login'),
                ),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
                            'forma3g/<id_clienthotel>'=>'hotelOrder/forma3g',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                 '<action:(login|logout|about)>' => 'users/<action>',
			),
		),
		
//		'db'=>array(
//			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
//		), 
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=lplayer_testcrm',
			'emulatePrepare' => true,
			'username' => 'lplayer_testcrm',
			'password' => 'qwelpo86',
			'charset' => 'utf8',
            'schemaCachingDuration'=>3600,
		),
        
        'site_db'=>array(
            'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=lplayer_devhotel',
			'emulatePrepare' => true,
			'username' => 'lplayer_devhotel',
			'password' => 'qwe123',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
		//	array(
//					'class'=>'CWebLogRoute',
//				),				
	
				
		 	),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'four.ibm-knight@hotmail.com',
        'domain'=>'http://admin-hotel.ru',
        'host'=>'local',
        'site_sinchronization' => true,
        'site_domain' => 'http://hotel72.loc',
		'rows_count' => 20,
		'uploads_folder' => '/uploads/',
        'siteDomain' => 'http://hotel72.loc'
	),
);