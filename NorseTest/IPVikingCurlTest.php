<?php

require_once __DIR__ . '/includes.php';

class IPVikingTestCurl extends PHPUnit_Framework_TestCase {
    const VALID_IP   = '67.13.46.123';

    /**
     * Ensure Request throws an exception if the cURL object does not implement the
     * \Norse\IPViking\CurlInterface interface
     *
     * @expectedException        Norse\IPViking\Exception_Curl
     * @expectedExceptionMessage cURL object provided does not implement \Norse\IPViking\CurlInterface
     * @expectedExceptionCode    182511
     */
    public function testCurlMustImplementInterface() {
        $config = array(
            'proxy'      => 'http://api.ipviking.com/api/',
            'api_key'    => '1234',
            'curl'       => new StdClass(),
        );
        $request = new Norse\IPViking\Request($config);
    }

    /**
     * Ensure Curl->init() throws expection on failure.
     *
     * @expectedException        Norse\IPViking\Exception_Curl
     * @expectedExceptionMessage cURL init failed with URL: http://init.fail.com/
     * @expectedExceptionCode    182512
     */
    public function testCurlInitFail() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://init.fail.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Ensure Curl->exec() throws exception on failure.
     *
     * @expectedException        Norse\IPViking\Exception_Curl
     * @expectedExceptionMessage cURL exec failed with error: exec fail
     * @expectedExceptionCode    1001
     */
    public function testCurlExecFail() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://exec.fail.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Ensure Curl->getInfo() throws exception on failure.
     *
     * @expectedException        Norse\IPViking\Exception_Curl
     * @expectedExceptionMessage cURL getinfo failed with error: getinfo fail
     * @expectedExceptionCode    1002
     */
    public function testCurlGetInfoFail() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://getinfo.fail.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Ensure Curl->setOpt() throws exception on failure.
     *
     * @expectedException        Norse\IPViking\Exception_Curl
     * @expectedExceptionMessage cURL setopt failed with error: setopt fail
     * @expectedExceptionCode    1003
     */
    public function disabled_testCurlSetOptFail() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://setopt.fail.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Ensure Curl->setOptArray() throws exception on failure.
     *
     * @expectedException        Norse\IPViking\Exception_Curl
     * @expectedExceptionMessage cURL setopt array failed with error: setoptarray fail
     * @expectedExceptionCode    1004
     */
    public function testCurlSetOptArrayFail() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://setoptarray.fail.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 400.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Bad Request
     * @expectedExceptionCode    400
     */
    public function testCurlUnexpectedResponse400() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '400',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 401.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Unauthorized
     * @expectedExceptionCode    401
     */
    public function testCurlUnexpectedResponse401() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '401',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 402.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Payment Required
     * @expectedExceptionCode    402
     */
    public function testCurlUnexpectedResponse402() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '402',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 405.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Method Not Allowed
     * @expectedExceptionCode    405
     */
    public function testCurlUnexpectedResponse405() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '405',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 409.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Conflict
     * @expectedExceptionCode    409
     */
    public function testCurlUnexpectedResponse409() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '409',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 415.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Unsupported Media Type
     * @expectedExceptionCode    415
     */
    public function testCurlUnexpectedResponse415() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '415',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 417.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Expectation Failed
     * @expectedExceptionCode    417
     */
    public function testCurlUnexpectedResponse417() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '417',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 418.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Wrong Action
     * @expectedExceptionCode    418
     */
    public function testCurlUnexpectedResponse418() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '418',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 419.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Wrong Category
     * @expectedExceptionCode    419
     */
    public function testCurlUnexpectedResponse419() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '419',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 420.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage GeoFilter Country Error
     * @expectedExceptionCode    420
     */
    public function testCurlUnexpectedResponse420() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '420',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 421.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage GeoFilter Region Error
     * @expectedExceptionCode    421
     */
    public function testCurlUnexpectedResponse421() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '421',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 422.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage GeoFilter City Error
     * @expectedExceptionCode    422
     */
    public function testCurlUnexpectedResponse422() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '422',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 423.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage GeoFilter Zip Error
     * @expectedExceptionCode    423
     */
    public function testCurlUnexpectedResponse423() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '423',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 424.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage XML Command Error
     * @expectedExceptionCode    424
     */
    public function testCurlUnexpectedResponse424() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '424',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 426.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Upgrade Required
     * @expectedExceptionCode    426
     */
    public function testCurlUnexpectedResponse426() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '426',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 500.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Internal Server Error
     * @expectedExceptionCode    500
     */
    public function testCurlUnexpectedResponse500() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '500',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 501.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Not Implemented
     * @expectedExceptionCode    501
     */
    public function testCurlUnexpectedResponse501() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '501',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 503.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Service Unavailable
     * @expectedExceptionCode    503
     */
    public function testCurlUnexpectedResponse503() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '503',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of response 509.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Bandwidth Limit Exceeded
     * @expectedExceptionCode    509
     */
    public function testCurlUnexpectedResponse509() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '509',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

    /**
     * Verify handling of unknown response codes.
     *
     * @expectedException        Norse\IPViking\Exception_API
     * @expectedExceptionMessage Unknown Response Code
     * @expectedExceptionCode    182550
     */
    public function testCurlUnexpectedResponseUnknown() {
        $ipv = new Norse\IPViking(array(
            'proxy'      => 'http://response.fail.com/',
            'api_key'    => '182550',
            'curl_class' => 'NorseTest\Curl',
        ));

        $ipq = $ipv->ipq(self::VALID_IP);
    }

}
