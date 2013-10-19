<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Europe/Chisinau");
ini_set('display_errors', 'On');
error_reporting(E_ALL);
define('APP_DIR', __DIR__.'/..');
define('FRONT_DIR', __DIR__.'/../../front');

set_include_path(get_include_path() . PATH_SEPARATOR . APP_DIR.'/vendor');