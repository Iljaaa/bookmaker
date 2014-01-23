<?php
return array(
	'class'=>'CLogRouter',
	'routes'=>array(
		array(
			'class'     =>'CFileLogRoute',
			'levels'    =>'error, warning, trace, info',
		),

		array(
			'class'     =>'CFileLogRoute',
			'levels'    =>'matchupdate',
			'logFile'   =>'matchupdate.log'
		),
		array(
			'class'     => 'CEmailLogRoute',
			'levels'    => 'matchupdateemail',
			'emails'    => 'the.ilja@gmail.com',
		),

		array(
			'class'     =>'CFileLogRoute',
			'levels'    =>'parser',
			'logFile'   =>'parser.log'
		),
		array(
			'class'     =>'CFileLogRoute',
			'levels'    =>'bets',
			'logFile'   =>'bets.log'
		),
	),
);