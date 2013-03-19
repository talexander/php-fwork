<?php
/**
 * User: tarakanov
 */

namespace App\Redis;


use App\App;
use App\Exception\Exception;

class Manager {

    /**
     * @var array of Client
     */
    private static $clients = array();

    public static function getClient($alias) {
        if(!isset(self::$clients[$alias])) {
            $conf = App::getInstance()->config('redis')->get($alias);
            if(isset($conf['host']) || !isset($conf['port'])) {
                throw new \InvalidArgumentException('Redis config not found for alias: ' . $alias);
            }
            self::$clients[$alias] = new Client($conf);
        }
        return self::$clients[$alias];
    }

    public function __destruct() {
        self::closeAll();
    }

    public static function closeAll() {
        foreach(self::$clients as $k => $client) {
            try {
                $client->close();
            } catch(Exception $e) {
                unset(self::$clients[$k]);
            }
        }
    }
}