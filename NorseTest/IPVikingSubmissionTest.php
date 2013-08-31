<?php

require_once __DIR__ . '/includes.php';

class IPVikingTestSubmission extends PHPUnit_Framework_TestCase {
    const VALID_IP   = '67.13.46.123';
    const INVALID_IP = '1234';

    const VALID_PROTOCOL_INT = 3;
    const VALID_PROTOCOL_STR = '3';
    const INVALID_PROTOCOL   = 'three';

    const VALID_CATEGORY_INT = 7;
    const VALID_CATEGORY_STR = '7';
    const INVALID_CATEGORY   = 'seven';

    const VALID_TIMESTAMP_INT = 1233210;
    const VALID_TIMESTAMP_STR = '1233210';
    const INVALID_TIMESTAMP   = 'Seven o\'clock last Tuesday';

    /**
     * Instance of Norse\IPViking using NorseTest\Curl set in setUp()
     */
    protected $_ipv;

    /**
     * Set up Norse\IPViking with defaults except for curl_class of NorseTest\Curl
     */
    public function setUp() {
        $this->_ipv = new Norse\IPViking(array(
            'proxy'      => 'http://submission.test.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));
    }

    /**
     * Invalid IP addresses supplied to Submission should result in an exception.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage The IP provided is not a valid IP address.
     * @expectedExceptionCode    182533
     */
    public function testSubmissionInvalidIP() {
        $submission = $this->_ipv->submission(self::INVALID_IP, self::VALID_PROTOCOL_INT, self::VALID_CATEGORY_INT, self::VALID_TIMESTAMP_INT);
    }

    /**
     * Invalid protocol supplied to Submission should result in an exception.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidSubmission
     * @expectedExceptionMessage Submission_Request::protocol values must be supplied as integers.
     * @expectedExceptionCode    182570
     */
    public function testSubmissionInvalidProtocol() {
        $submission = $this->_ipv->submission(self::VALID_IP, self::INVALID_PROTOCOL, self::VALID_CATEGORY_INT, self::VALID_TIMESTAMP_INT);
    }

    /**
     * Invalid category supplied to Submission should result in an exception.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidSubmission
     * @expectedExceptionMessage Submission_Request::category values must be supplied as integers.
     * @expectedExceptionCode    182571
     */
    public function testSubmissionInvalidCategory() {
        $submission = $this->_ipv->submission(self::VALID_IP, self::VALID_PROTOCOL_INT, self::INVALID_CATEGORY, self::VALID_TIMESTAMP_INT);
    }

    /**
     * Invalid timestamp supplied to Submission should result in an exception.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidSubmission
     * @expectedExceptionMessage Submission_Request::timestamp provided is invalid; expecting timestamp
     * @expectedExceptionCode    182572
     */
    public function testSubmissionInvalidTimestamp() {
        $submission = $this->_ipv->submission(self::VALID_IP, self::VALID_PROTOCOL_INT, self::VALID_CATEGORY_INT, self::INVALID_TIMESTAMP);
    }

    /**
     * Calls with all (int) values should succeed.
     */
    public function testSubmissionAllInt() {
        $submission = $this->_ipv->submission(self::VALID_IP, self::VALID_PROTOCOL_INT, self::VALID_CATEGORY_INT, self::VALID_TIMESTAMP_INT);
        $this->assertEquals('201', $submission->getHttpCode());
    }

    /**
     * Calls with all (string) values should succeed.
     */
    public function testSubmissionAllStr() {
        $submission = $this->_ipv->submission(self::VALID_IP, self::VALID_PROTOCOL_STR, self::VALID_CATEGORY_STR, self::VALID_TIMESTAMP_STR);
        $this->assertEquals('201', $submission->getHttpCode());
    }

}
