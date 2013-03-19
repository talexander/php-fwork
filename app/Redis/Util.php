<?php
/**
 * User: tarakanov
 */

namespace App\Redis;


class Util {
    /**
     * Ввалидация ключа для записи в редис
     * ограничения на длину ключа и набор допустимых символов
     * @param $key
     * @return bool
     */
    public static function isKeyValid($key) {
        $result = false;
        do {
            if(!is_string($key)) {
                break;
            }
            if(strlen($key) > 255) {
                break;
            }
            $result = preg_match('/^[A-z,:,_,0-9]+$/m', $key) == 1;
        } while(0);
        return $result;
    }

    public static function pack($val) {
        return json_encode($val);
    }

    public static function unpack($str) {
        return json_decode($str, true);
    }
}