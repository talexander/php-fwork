<?php

namespace App;

/**
 * User: tarakanov
 */

class Env {
    const DEV = 'DEV';
    const TEST = 'TEST';
    const PRODUCTION = 'PRODUCTION';

    private $alias;
    /**
     * @var null|Config\Reader
     */
    public $ConfigReader = null;

    public function __construct($alias) {
        if(!in_array($alias, array(self::DEV, self::TEST, self::PRODUCTION))) {
            throw new \App\Exception\EnvNotFound();
        }
        $this->alias = $alias;
        // список путей где искать конф файлы, в порядке увеличения приоритета конфигурационных значений
        $path = array(
            WEB_ROOT . 'conf/',
            WEB_ROOT . 'conf/' . strtolower($alias) . '/',
        );
        $this->ConfigReader = new \App\Config\Reader($path);
    }

    public function getAlias() {
        return $this->alias;
    }

    public function isDev() {
        return $this->getAlias() == self::DEV;
    }

    public function isTest() {
        return $this->getAlias() == self::TEST;
    }

    public function isProduction() {
        return $this->getAlias() == self::PRODUCTION;
    }

}