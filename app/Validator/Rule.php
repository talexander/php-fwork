<?php
/**
 * User: tarakanov
 */

namespace App\Validator;


abstract class Rule {
    abstract public function isSuitable();
}