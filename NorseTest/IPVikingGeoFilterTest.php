<?php

require_once __DIR__ . '/includes.php';

class IPVikingTestGeoFilter extends PHPUnit_Framework_TestCase {

    /**
     * Instance of Norse\IPViking using NorseTest\Curl set in setUp()
     */
    protected $_ipv;

    /**
     * Set up Norse\IPViking with defaults except for curl_class of NorseTest\Curl
     */
    public function setUp() {
        $this->_ipv = new Norse\IPViking(array(
            'proxy'      => 'http://geofilter.test.com/',
            'api_key'    => '1234',
            'curl_class' => 'NorseTest\Curl',
        ));
    }

    /**
     * Given a sample response, we verify the collection object is populated as expected.
     */
    protected function _validateResponse($collection) {
        $this->assertInternalType('array', $geofilter_array = $collection->getGeoFilters());
        foreach ($geofilter_array as $filter) {
            switch ($filter->getFilterID()) {
                case 423:
                    $this->_validateFilter423($filter);
                    break;
                case 433:
                    $this->_validateFilter433($filter);
                    break;
                case 443:
                    $this->_validateFilter443($filter);
                    break;
                case 1031:
                    $this->_validateFilter1031($filter);
                    break;
                default:
                    $this->fail('Invalid or unexpected result from Norse\IPViking\Settings_GeoFilter_Filter::getFilterID(): ' . var_export($filter->getFilterID(), true));
            }
        }
    }

    /**
     * The following methods verfiy a few different filters.
     */
    protected function _validateFilter423($filter) {
        $this->assertEquals(423, $filter->getFilterID());
        $this->assertEquals('allow', $filter->getAction());
        $this->assertEquals(0, $filter->getClientID());
        $this->assertEquals('country', $filter->getCategory());
        $this->assertEquals('US', $filter->getCountry());
        $this->assertEquals('-', $filter->getRegion());
        $this->assertEquals('-', $filter->getCity());
        $this->assertEquals('-', $filter->getZip());
        $this->assertEquals(4140, $filter->getHits());
    }

    protected function _validateFilter433($filter) {
        $this->assertEquals(433, $filter->getFilterID());
        $this->assertEquals('deny', $filter->getAction());
        $this->assertEquals(0, $filter->getClientID());
        $this->assertEquals('master', $filter->getCategory());
        $this->assertEquals('-', $filter->getCountry());
        $this->assertEquals('-', $filter->getRegion());
        $this->assertEquals('-', $filter->getCity());
        $this->assertEquals('-', $filter->getZip());
        $this->assertEquals(0, $filter->getHits());
    }

    protected function _validateFilter443($filter) {
        $this->assertEquals(443, $filter->getFilterID());
        $this->assertEquals('allow', $filter->getAction());
        $this->assertEquals(0, $filter->getClientID());
        $this->assertEquals('city', $filter->getCategory());
        $this->assertEquals('TW', $filter->getCountry());
        $this->assertEquals('04', $filter->getRegion());
        $this->assertEquals('PONG', $filter->getCity());
        $this->assertEquals('-', $filter->getZip());
        $this->assertEquals(0, $filter->getHits());
    }

    protected function _validateFilter1031($filter) {
        $this->assertEquals(1031, $filter->getFilterID());
        $this->assertEquals('allow', $filter->getAction());
        $this->assertEquals(0, $filter->getClientID());
        $this->assertEquals('master', $filter->getCategory());
        $this->assertEquals('-', $filter->getCountry());
        $this->assertEquals('-', $filter->getRegion());
        $this->assertEquals('-', $filter->getCity());
        $this->assertEquals('-', $filter->getZip());
        $this->assertEquals(0, $filter->getHits());
    }

    /**
     * Verify that the code produces the expected object given a contrived data source
     * from the test curl object.
     */
    public function testGetGeoFilterSettingsSuccess() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $geofilter = $this->_ipv->getGeoFilterSettings());
        $this->_validateResponse($geofilter);
    }

    /**
     * Verify that the code returns an instance of Settings_GeoFilter_Filter.
     */
    public function testGetNewGeoFilter() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter', $this->_ipv->getNewGeoFilter());
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Unexpected format for instantiation of Norse\IPViking\Settings_GeoFilter_Filter object.
     * @expectedExceptionCode    182580
     */
    public function testNewGeoFilterFilterFromString() {
        $geofilter = new Norse\IPViking\Settings_GeoFilter_Filter('foo');
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Unknown format for first argument of Settings_GeoFilter_Collection constructor.
     * @expectedExceptionCode    182582
     */
    public function testNewGeoFilterCollectionFromInt() {
        $geofilter = new Norse\IPViking\Settings_GeoFilter_Collection(123);
    }

    /**
     * Verify there are no errors when adding a GeoFilter.
     */
    public function testAddGeoFilter() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->addGeoFilter($filter));
    }

    /**
     * Verify there are no errors when deleting a GeoFilter.
     */
    public function testDeleteGeoFilter() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->deleteGeoFilter($filter));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with command 'add'.
     */
    public function testUpdateGeoFiltersCommandAdd() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filters = array($filter, array('command' => 'add', 'client_id' => 0, 'action' => 'allow', 'category' => 'master'));

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with command 'delete'.
     */
    public function testUpdateGeoFiltersCommandDelete() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('delete');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Instance of Settings_GeoFilter_Filter requires valid command value.
     * @expectedExceptionCode    182581
     */
    public function testUpdateGeoFiltersNoCommand() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::command;
     * @expectedExceptionCode    182589
     */
    public function testUpdateGeoFiltersMissingCommand() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand(null);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'command' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::command;
     * @expectedExceptionCode    182589
     */
    public function testUpdateGeoFiltersInvalidCommand() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify that we can set the filter's 'client_id' value.
     */
    public function testUpdateGeoFiltersValidClientIDValid() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setClientID(0);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'client_id' value is a string.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::client_id;
     * @expectedExceptionCode    182584
     */
    public function testUpdateGeoFiltersInvalidClientIDString() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setClientID('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'client_id' value is negative.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::client_id;
     * @expectedExceptionCode    182584
     */
    public function testUpdateGeoFiltersInvalidClientIDNegative() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setClientID(-5);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with action 'allow'.
     */
    public function testUpdateGeoFiltersActionAllow() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setAction('allow');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with action 'deny'.
     */
    public function testUpdateGeoFiltersActionDeny() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('delete');
        $filter->setAction('deny');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'action' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::action;
     * @expectedExceptionCode    182585
     */
    public function testUpdateGeoFiltersMissingAction() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setAction(null);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'action' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::action;
     * @expectedExceptionCode    182585
     */
    public function testUpdateGeoFiltersInvalidAction() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setAction('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'master'.
     */
    public function testUpdateGeoFiltersCategoryMaster() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('master');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'zip'.
     */
    public function testUpdateGeoFiltersCategoryZip() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('ZIP');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'city'.
     */
    public function testUpdateGeoFiltersCategoryCity() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('City');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'region'.
     */
    public function testUpdateGeoFiltersCategoryRegion() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('region');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with category 'country'.
     */
    public function testUpdateGeoFiltersCategoryCountry() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('cOuNtRy');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'category' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::category;
     * @expectedExceptionCode    182586
     */
    public function testUpdateGeoFiltersMissingCategory() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory(null);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'category' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::category;
     * @expectedExceptionCode    182586
     */
    public function testUpdateGeoFiltersInvalidCategory() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCategory('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with country 'US'.
     */
    public function testUpdateGeoFiltersCountryUpperCase() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry('US');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with country 'us'.
     */
    public function testUpdateGeoFiltersCountryLowerCase() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry('us');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify there are no errors when updating a GeoFilter with country '-'.
     */
    public function testUpdateGeoFiltersCountry() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry('-');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'country' value is missing.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::country;
     * @expectedExceptionCode    182587
     */
    public function testUpdateGeoFiltersMissingCountry() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry(null);
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the filter's 'country' value is invalid.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidGeoFilter
     * @expectedExceptionMessage Invalid value for Settings_GeoFilter_Filter::country;
     * @expectedExceptionCode    182587
     */
    public function testUpdateGeoFiltersInvalidCountry() {
        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Filter',     $filter = $this->_ipv->getNewGeoFilter());
        $filter->setCommand('add');
        $filter->setCountry('foo');
        $filters = array($filter);

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = $this->_ipv->updateGeoFilters($filters));
    }

    /**
     * Verify the expected exception is thrown when the cURL response does not have a 'geofilters' element.
     *
     * @expectedException        Norse\IPViking\Exception_UnexpectedResponse
     * @expectedExceptionMessage Expecting element 'geofilters' in response:
     * @expectedExceptionCode    182541
     */
    public function testResponseNoGeoFilters() {
        $json = '{"response": "invalid"}';
        new Norse\IPViking\Settings_GeoFilter_Collection($json);
    }

    /**
     * Verify the expected exception is thrown when the cURL response 'geofilters' element is not formatted as expected.
     *
     * @expectedException        Norse\IPViking\Exception_UnexpectedResponse
     * @expectedExceptionMessage Unexpected data type for 'geofilters' in response:
     * @expectedExceptionCode    182542
     */
    public function testResponseGeoFiltersNotValidType() {
        $json = '{"geofilters": "invalid"}';
        new Norse\IPViking\Settings_GeoFilter_Collection($json);
    }

    /**
     * When the geofilters array is empty, getGeoFilterXML should return null.
     */
    public function testGetGeoFilterXMLEmpty() {
        $json = '{"geofilters": [ ]}';
        $collection = new Norse\IPViking\Settings_GeoFilter_Collection($json);
        $this->assertNull($collection->getGeoFilterXML());
    }

    /**
     * Ensure the format of the XML provided by getGeoFilterXML is as expected.
     */
    public function testGetGeoFilterXMLFormat() {
        $json = '{"geofilters":[{"command":"add","action":"Allow","category":"City","country":"TW","region":"04","city":"PONG","zip":"-","hits":"0"},{"command":"delete","action":"Allow","category":"Country","country":"US","region":"-","city":"-","zip":"-"}]}';
        $xml  = <<<XML
<?xml version=1.0?>
<ipviking>
    <geofilter>
        <filters>
            <command>add</command>
            <action>allow</action>
            <category>city</category>
            <country>TW</country>
            <region>04</region>
            <city>PONG</city>
            <zip>-</zip>
        </filters>
        <filters>
            <command>delete</command>
            <action>allow</action>
            <category>country</category>
            <country>US</country>
            <region>-</region>
            <city>-</city>
            <zip>-</zip>
        </filters>
    </geofilter>
</ipviking>
XML;

        $collection = new Norse\IPViking\Settings_GeoFilter_Collection($json);
        $this->assertEquals($xml, $collection->getGeoFilterXML());
    }

    /**
     * Ensure we can instantiate Norse\IPViking\Settings_GeoFilter_Collection from an object.
     */
    public function testInstantiateCollectionFromObject() {
        $filter = new StdClass();
        $filter->risk_id = 1;
        $filter->command = 'add';
        $filter->risk_attribute = 'Test Attribute';
        $filter->risk_good_value = 75;
        $filter->risk_bad_value  = 25;

        $this->assertInstanceOf('Norse\IPViking\Settings_GeoFilter_Collection', $collection = new Norse\IPViking\Settings_GeoFilter_Collection($filter));
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
        $ipq = $this->_ipv->getGeoFilterSettings();
    }

}
