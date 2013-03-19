<?php
/**
 * User: tarakanov
 */

namespace App\Validator;


abstract class Base {

    private $options;
    private $errors = array();
    private $validated = false;

    public function __construct(array $options = array()) {
        $this->options = $options;
    }

    abstract public function isValid($item);

    public function getErrors() {
        if(!$this->validated) {
            throw new Exception\NotValidatedYet();
        }
        return $this->errors;
    }
}