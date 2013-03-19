<?php
/**
 * User: tarakanov
 */

namespace App\Redis;


use App\App;

class Session {

    /**
     * Идентификатор пользователя для которого создается сессия
     * @var int
     */
    private $id;

    /**
     * Хеш ключ сессии для доступа к данным в редисе
     * @var string
     */
    private $hash;

    /**
     * @var Client
     */
    private static $client;

    /**
     * Исходные данные сессии
     * @var array
     */
    private $sourceData;
    private $modifiedData;

    /**
     * Загружены ли данные по сессии
     * @var bool
     */
    private $loaded = false;

    /**
     * Сохранены ли изменения сессии в сторадже
     * @var bool
     */
    private $modifiedDataSaved = true;

    /**
     * Список полей которые возможно сохранять в сессию
     * @var Array
     */
    private $fields;


    /**
     * Идентификатор пользователя
     * @param integer $id
     */
    public function __construct($id) {
        if(!ctype_digit($id) || empty($id)) {
            throw new \InvalidArgumentException('Wrong value of session id ' . $id);
        }
        $this->id = intval($id);
        $salt = App::getInstance()->config('session')->hash_salt;
        $this->hash = hash('sha256', $this->id . $salt);
        $this->fields = App::getInstance()->config('session')->fields;
    }

    public function getField($field) {
        if($this->loaded) {
            // @TODO: отдавать дефолтное значение если такого поля нет
            return $this->sourceData[$field];
        }
        return $this->getClient()->hGet($this->getStoredKey(), $field);
    }

    public function setField($field, $val) {
        // @TODO: сделать фильтр полей которые можно записывать в сессию
        $this->modifiedData[$field] = $val;
        $this->modifiedDataSaved = false;
    }

    public function save() {
        if(is_array($this->modifiedData) && !empty($this->modifiedData)) {
            $this->getClient()->hMSet($this->getStoredKey(), $this->modifiedData);
            $this->modifiedDataSaved = true;
            if($this->loaded) {
                foreach($this->modifiedData as $k => $v) {
                    $this->sourceData[$k] = $v;
                }
            }
            $this->modifiedData = array();
        }
    }

    public function incrField($field, $incr = 1) {
        if(!ctype_digit($incr)) {
            throw new \InvalidArgumentException(sprintf('Wrong value for increment session field "%s", val: %s', $field, $incr));
        }
        if(!is_string($field)) {
            throw new \InvalidArgumentException(sprintf('Session field name have\ not string type: "%s"', $field));
        }
        // @TODO: обновить загруженные данные
        return $this->getClient()->hIncrBy($this->getStoredKey(), $field, $incr);
    }


    public function mergeData(array $data) {
        // @TODO: сделать фильтр полей которые можно записывать в сессию
        foreach($data as $k => $v) {
            $this->modifiedData[$k] = $v;
        }
    }

    /**
     * Перечитывание данных сессии
     */
    public function refresh() {
        $this->loaded = false;
        $this->getData();
    }

    /**
     * Получение всех данных хранящихся в сессии
     * @return array
     */
    public function getData() {
        if(!$this->loaded) {
            $this->load();
        }
        return $this->sourceData;
    }

    public function load() {
        $client = $this->getClient();
        $data = $client->hGetAll($this->getStoredKey());
        if(is_array($data)) {
            $this->sourceData = $data;
        } else {
            $this->sourceData = array();
        }
        $this->loaded = true;
        return $this;
    }

    private function getStoredKey() {
        return sprintf('s5n_%s', $this->hash);
    }

    private function getClient() {
        if(!isset(self::$client)) {
            self::$client = Manager::getClient('session');
        }
        return self::$client;
    }
}