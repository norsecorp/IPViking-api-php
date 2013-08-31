<?php

require_once __DIR__ . '/includes.php';

class IPVikingTestRequest extends PHPUnit_Framework_TestCase {

    /**
     * Ensure Request throws an exception if the proxy is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage Proxy URL required for Request class functionality.
     * @expectedExceptionCode    182534
     */
    public function testProxyMustExist() {
        $config = array(
            'api_key' => '1234',
            'curl' => new NorseTest\Curl(),
        );
        $request = new Norse\IPViking\Request($config);
    }

    /**
     * Ensure Request throws an exception if the API Key is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage API Key required for Request class functionality.
     * @expectedExceptionCode    182535
     */
    public function testAPIKeyMustExist() {
        $config = array(
            'proxy' => 'http://api.ipviking.com/api/',
            'curl' => new NorseTest\Curl(),
        );
        $request = new Norse\IPViking\Request($config);
    }

    /**
     * Ensure Request throws an exception if the cURL object is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage cURL object required for Request class functionality.
     * @expectedExceptionCode    182536
     */
    public function testCurlMustExist() {
        $config = array(
            'proxy' => 'http://api.ipviking.com/api/',
            'api_key' => '1234',
        );
        $request = new Norse\IPViking\Request($config);
    }

    /**
     * Ensure Request class instantiated properly when all requirements are met.
     */
    public function testValidRequest() {
        $config = array(
            'proxy' => 'http://api.ipviking.com/api/',
            'api_key' => '1234',
            'curl' => new NorseTest\Curl(),
        );
        $this->assertInstanceOf('Norse\IPViking\Request', $request = new Norse\IPViking\Request($config));
    }

}
