<?php

error_reporting(E_ALL | E_STRICT);

define('WEB_ROOT', dirname(dirname(__FILE__)). '/');
require WEB_ROOT . 'vendor/autoload.php';
require WEB_ROOT . 'app/bootstrap.php';


var_dump(123);


$app = new \App\App(\App\Env::DEV);
$app->initialize();

$data = array(
    'k1' => 'sfas',
);
$validator = new \App\Validation\Validator($data);
$validator->rule('k1', 'notEmpty');
$validator->rule('k1', 'minLength', array(':value', 6));
print 'check: ' . intval($validator->check()) . "\n";

if(!intval($validator->check())) {
    print_r($validator->errors('validation'));
}




exit;

//$App->run();

//echo Request::factory(TRUE, array(), FALSE)
//    ->execute()
//    ->send_headers(TRUE)
//    ->body();
