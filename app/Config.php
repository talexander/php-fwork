<?php
/**
 * User: tarakanov
 */

namespace App;


class Config extends \RecursiveArrayIterator {

    private $source;

    public function __construct(array $config) {
        $this->source = $config;
        parent::__construct($config);
    }
    public function export() {
        return $this->source;
    }

    public function __get($field) {
        return $this[$field];
    }
}