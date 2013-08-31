<?php

require_once __DIR__ . '/includes.php';

class IPVikingTestIPQ extends PHPUnit_Framework_TestCase {
    const VALID_IP   = '67.13.46.123';
    const INVALID_IP = '1234';

    /**
     * Instance of Norse\IPViking using NorseTest\Curl set in setUp()
     */
    protected $_ipv;

    /**
     * Set up Norse\IPViking with defaults except for curl_class of NorseTest\Curl
     */
    public function setUp() {
        $this->_ipv = new Norse\IPViking(array(
            'proxy'      => 'http://ipq.test.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));
    }

    /**
     * Validate the expected values are set in the given IPQ Response object.
     *
     * @param Norse\IPViking\IPQ\Response An IPQ Response object.
     */
    protected function _validateResponse($ipq) {
        $this->assertEquals('67.13.46.123', $ipq->getIP());
        $this->assertEquals(0, $ipq->getTransID());
        $this->assertEquals(0, $ipq->getClientID());
        $this->assertEquals(0, $ipq->getCustomID());
        $this->assertEquals('ipq', $ipq->getMethod());
        $this->assertEquals('NXDOMAIN', $ipq->getHost());
        $this->assertEquals(83, $ipq->getRiskFactor());
        $this->assertEquals('orange', $ipq->getRiskColor());
        $this->assertEquals('High', $ipq->getRiskName());
        $this->assertEquals('High risk Involved', $ipq->getRiskDesc());
        $this->assertEquals('2013-08-22T18:50:31-04:00', $ipq->getTimestamp());
        $this->assertEquals(13, $ipq->getFactorEntriesCount());

        $this->assertInstanceOf('Norse\IPViking\IPQ_IPInfo', $ipInfo = $ipq->getIPInfo());
        $this->assertEquals('n/a', $ipInfo->getAutonomousSystemNumber());
        $this->assertEquals('n/a', $ipInfo->getAutonomousSystemName());

        $this->assertInstanceOf('Norse\IPViking\IPQ_GeoLoc', $geoloc = $ipq->getGeoLoc());
        $this->assertEquals('United States', $geoloc->getCountry());
        $this->assertEquals('US', $geoloc->getCountryCode());
        $this->assertEquals('-', $geoloc->getRegion());
        $this->assertEquals('-', $geoloc->getRegionCode());
        $this->assertEquals('-', $geoloc->getCity());
        $this->assertEquals('38', $geoloc->getLatitude());
        $this->assertEquals('-97', $geoloc->getLongitude());
        $this->assertEquals('Century Link', $geoloc->getInternetServiceProvider());
        $this->assertEquals('Century Link', $geoloc->getOrganization());

        $this->assertInternalType('array', $entries = $ipq->getEntries());
        $this->assertInstanceOf('Norse\IPViking\IPQ_Entry', $entry = array_pop($entries));
        $this->assertEquals('2', $entry->getCategoryID());
        $this->assertEquals('Bogon Unadv', $entry->getCategoryName());
        $this->assertEquals('25', $entry->getCategoryFactor());
        $this->assertEquals('31', $entry->getProtocolID());
        $this->assertEquals('IP unadvertised', $entry->getProtocolName());
        $this->assertEquals('Unadvertised IP', $entry->getOverallProtocol());
        $this->assertEquals('2013-08-16T04:31:07-04:00', $entry->getLastActive());

        $this->assertInternalType('array', $factoring_reasons = $ipq->getFactoringReasons());
        $this->assertInstanceOf('Norse\IPViking\IPQ_FactoringReason', $reason = array_pop($factoring_reasons));
        $this->assertEquals('4.1', $reason->getCountryRiskFactor());
		$this->assertEquals('0', $reason->getRegionRiskFactor());
		$this->assertEquals('8', $reason->getIPResolveFactor());
		$this->assertEquals('10', $reason->getAsnRecordFactor());
		$this->assertEquals(5, $reason->getAsnThreatFactor());
		$this->assertEquals('20', $reason->getBgpDelegationFactor());
		$this->assertEquals('-2', $reason->getIanaAllocationFactor());
		$this->assertEquals('-1', $reason->getIPVikingPersonalFactor());
		$this->assertEquals(19, $reason->getIPVikingCategoryFactor());
		$this->assertEquals(0, $reason->getIPVikingGeofilterFactor());
		$this->assertEquals(0, $reason->getIPVikingGeofilterRule());
		$this->assertEquals(0, $reason->getGeoMatchDistance());
		$this->assertEquals(0, $reason->getGeoMatchFactor());
		$this->assertEquals('20', $reason->getDataAgeFactor());
		$this->assertEquals('0', $reason->getSearchVolumeFactor());
    }

    /**
     * Verify that the code produces the expected object given a contrived data source
     * from the test curl object.
     */
    public function testIPQSuccess() {
        $this->assertInstanceOf('Norse\IPViking\IPQ_Response', $ipq = $this->_ipv->ipq(self::VALID_IP));

        $this->_validateResponse($ipq);
    }

    /**
     * Invalid IP addresses supplied to IPQ should result in an exception.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage The IP provided is not a valid IP address.
     * @expectedExceptionCode    182530
     */
    public function testIPQInvalidIP() {
        $ipq = $this->_ipv->ipq(self::INVALID_IP);
    }

    /**
     * Valid IP addresses supplied to getIPQRequest should result in an instance of a
     * Norse\IPViking\IPQ_Request object.
     */
    public function testGetIPQRequestValidIP() {
        $this->assertInstanceOf('Norse\IPViking\IPQ_Request', $this->_ipv->getIPQRequest(self::VALID_IP));
    }

    /**
     * Invalid IP addresses supplied to getIPQRequest should result in an exception.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage The IP provided is not a valid IP address.
     * @expectedExceptionCode    182531
     */
    public function testGetIPQRequestInvalidIP() {
        $ipq = $this->_ipv->getIPQRequest(self::INVALID_IP);
    }

    /**
     * Valid IP addresses supplied to XML should result in an XML response.
     */
    public function testXMLValidIP() {
        $this->assertStringStartsWith('<?xml version="1.0"?>', $this->_ipv->xml(self::VALID_IP));
    }

    /**
     * Invalid IP addresses supplied to XML should result in an exception.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidRequest
     * @expectedExceptionMessage The IP provided is not a valid IP address.
     * @expectedExceptionCode    182532
     */
    public function testXMLInvalidIP() {
        $ipq = $this->_ipv->xml(self::INVALID_IP);
    }

    /**
     * Ensure that an invalid JSON response results in an error.
     *
     * @expectedException        Norse\IPViking\Exception_Json
     * @expectedExceptionMessage Error decoding json response:
     * @expectedExceptionCode    4
     */
    public function testInvalidJSONResponse() {
        $this->_ipv = new Norse\IPViking(array(
            'proxy'      => 'http://json.fail.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));
        $ipq = $this->_ipv->ipq(self::VALID_IP);
    }

}
