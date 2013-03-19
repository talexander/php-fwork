<?php
/**
 * User: tarakanov
 */

namespace App\Redis;

use App\Exception\NotImplement;

class Lock {
    /**
     * @var Client
     */
    private static $client;

    private static $acquired = array();

    const KEY_PREFIX = 'lock_';

    public function acquire($key, $ttlMs, $retry = 1) {
        if(!empty(static::$acquired[$key])) {
            return true;
        }
        $retry = max(intval($retry), 1);
        $totalRetry = max($retry, 1);
        $mt = microtime(true);
        $meta = array(
            't' => $mt,
            'pid' => static::getPid(),
            'release' => $mt + $ttlMs / 1000,
        );
        $client = static::getClient();

        $redisErr = $result = false;
        $fullKey = static::getKey($key);
        do {
            try{
                $result = $client->setNx($fullKey, $meta);
                // @TODO: проставлять время жизни ключа
            } catch(\RedisException $e) {
                $redisErr = true;
            }

            if(!$result) {
                if(!$redisErr) {
                    try{
                        $data = $client->get($fullKey);
                        if(!empty($data)) {
                            $data = Util::unpack($data);
                            if(microtime(true) > $data['release']) {
                                \App\App::getInstance()->logger->warning('Lost lock ' . $key);
                                $client->del($fullKey);
                                continue;
                            }
                        }
                    } catch(\Exception $e) {
                        \App\App::getInstance()->logger->warning('Exception while attemping set lock ' . $e->getMessage());
                    }
                }
                usleep(20000 * ($totalRetry - $retry));
            } else {
                static::$acquired[$key] = $mt;
                break;
            }
        } while(--$retry > 0);

        return $result;
    }

    private static function getKey($key) {
        return static::KEY_PREFIX . $key;
    }

    public function prolongate($ttlMs) {
        // @TODO: implement
        throw new NotImplement(__METHOD__ . ' not implement.');
    }

    public function release($key) {
        if(!isset(static::$acquired[$key])) {
            throw new \App\Exception\InvalidArgument('Lock for key: ' . $key . ' do not acquired!');
        }
        $fullKey = static::getKey($key);
        self::getClient()->del($fullKey);
        unset(static::$acquired[$key]);
    }

    /**
     * Вынести
     * @return int
     */
    private static function getPid() {
        static $pid;
        if(!isset($pid)) {
            $pid = getmygid();
        }
        return $pid;
    }


    /**
     * @return Client
     */
    private function getClient() {
        if(!isset(self::$client)) {
            self::$client = Manager::getClient('lock');
        }
        return self::$client;
    }
}