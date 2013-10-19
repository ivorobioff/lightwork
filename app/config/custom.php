<?php
return array(
	'default_path' => array('controller' => 'Planner'),
	'is_production' => false,
	'db' => array(
		'default' => array(
			'host' => 'localhost',
			'username' => 'root',
			'password' => '1234',
			'dbname' => 'mlogger'
		),
		'test' => array(
			'host' => 'localhost',
			'username' => 'root',
			'password' => '1234',
			'dbname' => 'test'
		)
	),
	'js_composer' => array(
		'app_path' => FRONT_DIR.'/js/app', //путь к js приложению
		'bin_path' => FRONT_DIR.'/js/app/bin', // путь куда будет сохраняться готовый js файл
	)
);