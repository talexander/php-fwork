<?php
/**
 * User: tarakanov
 */

namespace App\Config;


class Reader {

    private static $loaded_configs = array();

    // массив путей в порядке убывания приоритета для поиска конфига
    private $sorted_path;

    /**
     * @var null|Array
     */
    private $data = null;

    public function __construct(Array $path) {
        $this->sorted_path = $path;
    }

    /**
     * @param $name string config name
     * @return mixed
     * @throws Exception\NotFound
     */
    public function get($name) {
        $md = $this->getConfigHash($name);
        if(!static::isConfigLoaded($md)) {
            $this->loadConfig($name);
        }

        return static::$loaded_configs[$md];
    }


    /**
     * @param String $name
     * @return String
     */
    private function getConfigHash($name) {
        static $hashes;
        if(!isset($hashes[$name])) {
            $hashes[$name] = md5(join(";", $this->sorted_path) . $name);
        }

        return $hashes[$name];
    }

    private function loadConfig($name) {
        $config_merged = array();
        $found = 0;
        foreach($this->sorted_path as $dir) {
            $file = $dir . $name . ".php" ;
            try {
                $config = include_once $file;
                if(is_array($config)) {
                     $config_merged = array_merge($config_merged, $config);
                }
                $found++;
            } catch(\App\Exception\Warning $e) {
                // если не найдем ни в одной директории, выбросим исключение уже после цикла
            }
        }

        if(!is_array($config_merged)) {
            throw new Exception\InvalidData();
        } else if($found == 0) {
            throw new Exception\NotFound("Config " . $name . sprintf(" not found (scanned dirs: %s)", join(" ;", $this->sorted_path)));
        }

        static::$loaded_configs[$this->getConfigHash($name)] = new \App\Config($config_merged);
    }

    protected static function isConfigLoaded($md) {
        return !empty(static::$loaded_configs[$md]);
    }

    public function __destruct() {
        static::$loaded_configs = array();
    }
}