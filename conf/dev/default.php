<?php

return array(
    'mysql' => array(
        'default' => array('host' => '127.0.0.1', 'port' => 3306, 'psw' => '@!mysql'),
    ), // env must override this param
    'redis' => array(
        'default' => array('host' => '127.0.0.1', 'port' => 6379, 'db' => 0),
        'session' => array('host' => '127.0.0.1', 'port' => 6379, 'db' => 1)
    ), // env must override this param
);