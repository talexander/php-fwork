<?php
/**
 * User: tarakanov
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))). '/vendor/phpunit/phpunit/PHPUnit/Autoload.php';

class ConfigTest extends PHPUnit_Framework_TestCase {
    const TMP_DIR1 = 'tmp/';
    public function testCheck() {
        $this->assertEquals(0, 0);
    }
}