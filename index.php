<?php
use LightWork\Web;

require_once __DIR__.'/app/config/app.php';
require_once __DIR__.'/app/Web.php';

$app = new Web();
$app->start();