<?php

/**
 * User: tarakanov
 */

require_once WEB_ROOT . '/app/App.php';

spl_autoload_register(array('\App\App', 'auto_load'));

ini_set('unserialize_callback_func', 'spl_autoload_call');
