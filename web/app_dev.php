<?php

error_reporting(E_ALL | E_STRICT);

define('WEB_ROOT', dirname(dirname(__FILE__)). '/');
require WEB_ROOT . 'vendor/autoload.php';
require WEB_ROOT . 'app/bootstrap.php';





$app = new App\App(\App\Env::DEV);
$app->initialize();

$a['sdf'];

$config = $app->config();

var_dump("Ok");

//$App->run();

//echo Request::factory(TRUE, array(), FALSE)
//    ->execute()
//    ->send_headers(TRUE)
//    ->body();
