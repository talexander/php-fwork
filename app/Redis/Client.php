<?php
/**
 * User: tarakanov
 */

namespace App\Redis;


use App\Exception\Exception;

class Client extends \Redis {

    private $host;
    private $port;
    private $tmSec = 5; // timeout

    private $connected = false;

    const PONG = '';

    private $cmdExecCount = 0;

    public function __construct($conf) {
        parent::__construct();
        $this->host = $conf['host'];
        $this->port = $conf['port'];
        if(isset($conf['tm_sec'])) {
            $this->tmSec = intval($conf['tm_sec']);
        }
    }

    private function connect() {
        $tryTotal = $try = 5;
        do {
            try {
                parent::connect($this->host, $this->port, $this->tmSec);
                $this->connected = true;
            } catch(\Exception $e) {
                if($try == 1) {
                    throw $e;
                }
            }
            usleep(($tryTotal - $try)*20000);
        } while(--$try > 0);
    }

    public function ping() {
        $result = false;
        try{
            return parent::ping() == static::PONG;
        } catch(\RedisException $e) {
            $this->connected = false;
        }
        return $result;
    }


    public function checkConnection() {
        if(!$this->connected) {
            $this->connect();
        }
        if(!$this->ping()) {
            $this->connect();
        }
    }

    private function checkKey($key) {
        if(!Util::isKeyValid($key)) {
            throw new Exception\InvalidKey($key);
        }
        return true;
    }

    public function setNx($key, $value) {
        $this->checkKey($key);
        $this->checkConnection();
        return parent::setNx($key, $value);
    }

    public function get($key) {
        $this->checkKey($key);
        $this->checkConnection();
        return parent::get($key);
    }

    public function del($key) {
        $this->checkConnection();
        return parent::del($key);
    }


    ###### методы для работы с хешами ######

    public function hGetAll($key) {
        $this->checkConnection();
        return parent::hGetAll($key);
    }

    public function hGet($key, $field) {
        $this->checkConnection();
        return parent::hGet($key, $field);
    }

    public function hSet($key, $field, $val) {
        $this->checkKey($key);
        $this->checkConnection();
        return parent::hSet($key, $field, $val);
    }

    public function hSetNx($key, $field, $val) {
        $this->checkKey($key);
        $this->checkConnection();
        return parent::hSetNx($key, $field, $val);
    }

    public function hMGet($key, array $fields) {
        if(empty($fields)) {
            return array();
        }
        $this->checkConnection();
        return parent::hmGet($key, $fields);
    }

    public function hIncrBy($key, $field, $val) {
        if(!ctype_digit($val)) {
            throw new \InvalidArgument('Wrong value of incr: ' . $val);
        }
        $this->checkKey($key);
        $this->checkConnection();
        return parent::hIncrBy($key, $field, $val);
    }

    public function hMSet($key, array $data) {
        if(empty($data)) {
            return true;
        }
        $this->checkKey($key);
        $this->checkConnection();
        return parent::hmSet($key, $data);
    }


}