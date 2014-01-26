<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Dota 2 bookmaker prototype',
	'timeZone' => 'Europe/Madrid',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.forms.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'    =>true,
			'class'				=> 'WebUser',
		),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'         =>'path',
			'showScriptName'	=> false,
			'rules'=>array(
				'champ/<id:\d+>'            => 'champs/view',
				'champ/<id:\d+>/edit'       => 'champs/edit',

				'team/<id:\d+>/edit'        => 'teams/edit',

				'match/<id:\d+>'            => 'matches/view',
				'match/<match:\d+>/bet'     => 'bets/add',
				'match/<match:\d+>/delete'  => 'matches/delete',
				'match/<match:\d+>/result'   => 'matches/result',

				'mybets'                    => 'bets/user',

				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/

		// uncomment the following to use a MySQL database
		'db'=> require("mysql.config.php"),


		//
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'cache'=>array(
			'class'=>'system.caching.CFileCache',
		),

		//
		'log'=> require ('log.config.php'),

		//
		'firephp'	=> array (
			'class'=>'application.extensions.firephp.FirePHP',
		),

		//
		'wiky'	=> array (
			'class'=>'application.extensions.wiky.wiky',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'vangel@yandex.ru',
	),
);