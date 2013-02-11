<?php
/**
 * User: tarakanov
 */

namespace App;


class ErrorHandler {

    public static function processError($code, $errstr, $errfile = NULL, $errline = NULL, $errContext = NULL) {
            if (error_reporting() & $code) {
                switch($code) {
                    case E_ERROR:
                    case E_CORE_ERROR:
                    case E_COMPILE_ERROR:
                    case E_USER_ERROR:
                        throw new Exception\Fatal($errstr, $code, 0, $errfile, $errline);
                        break;

                    case E_NOTICE:
                    case E_USER_NOTICE:
                        throw new Exception\Notice($errstr, $code, 0, $errfile, $errline);
                        break;

                    case E_CORE_WARNING:
                    case E_WARNING:
                    case E_COMPILE_WARNING:
                    case E_USER_WARNING:
                        throw new Exception\Warning($errstr, $code, 0, $errfile, $errline);
                        break;
                }
            }

            // execute the PHP error handler
            return FALSE;
    }

    public static function processException() {

    }
}