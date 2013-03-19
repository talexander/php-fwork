<?php

return array(
    'locale' => 'ru_RU.utf-8',
    'timezone' => 'Europe/Moscow',
    'mysql' => array(), // env must override this param
    'redis' => array(), // env must override this param
    'path' => array(
        'log' => 'var/log/',
        'pids' => 'var/run/',
        'tmp' => 'var/tmp/',
    ),
    'loggers' => array(
        'app' => array('app.log', 'level' => 100,),
    ),
);