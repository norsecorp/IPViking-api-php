<?php

require_once __DIR__ . '/includes.php';

class BasicTest extends PHPUnit_Framework_TestCase {

    /**
     * Ensure PHP Unit is installed.
     */
    public function testPHPUnit() {
        return true;
    }

    /**
     * Ensure we can access the objects installed via composer.
     */
    public function testAutoLoad() {
        $this->assertInstanceOf('Norse\IPViking\Exception', new Norse\IPViking\Exception());
    }

}
