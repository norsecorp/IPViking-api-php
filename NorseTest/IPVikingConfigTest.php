<?php

/**
 * This collection of tests examine the configuration options for the IPViking class.
 */

require_once __DIR__ . '/includes.php';

class IPVikingTestConfig extends PHPUnit_Framework_TestCase {

    /**
     * This compares the configuration of the given Norse\IPViking instantiation to the
     * expected default settings.
     */
    protected function _verifyIPVDefaults(Norse\IPViking $ipv) {
        $this->assertEquals('http://beta.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals('8292777557e8eb8bc169c2af29e87ac07d0f1ac4857048044402dbee06ba5cea', $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * An empty invocation should result in a default configuration.
     */
    public function testLoadDefaultConfiguration() {
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking());
        $this->_verifyIPVDefaults($ipv);
    }

    /**
     * An empty array passed to the constructor should result in a default configuration.
     */
    public function testLoadConfigurationArrayEmptyIsDefault() {
        $config = array();
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));
        $this->_verifyIPVDefaults($ipv);
    }

    /**
     * An array passed to the constructor can define a different curl class.
     */
    public function testLoadConfigurationArrayDefineDifferentCurlClass() {
        $test_curl = 'NorseTest\Curl';
        $config = array('curl_class' => $test_curl);
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://beta.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals('8292777557e8eb8bc169c2af29e87ac07d0f1ac4857048044402dbee06ba5cea', $ipv->getApiKey());
        $this->assertInstanceOf($test_curl, $ipv->getCurl());
    }

    /**
     * Ensure appropriate exception is thrown when configuration value provided to
     * Norse\IPViking::__construct is not a recognized format.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to determine format of provided configuration.
     * @expectedExceptionCode    182500
     */
    public function testLoadConfigurationFail() {
        $config = new StdClass();
        $ipv = new Norse\IPViking($config);
    }

    /**
     * When overriding the default curl class, the object must be a valid instance of the
     * Norse\IPViking\curl_interface.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Curl class must implement Norse\IPViking\CurlInterface.
     * @expectedExceptionCode    182500
     */
    public function testLoadConfigurationArrayDefineDifferentCurlClassNoInterface() {
        $config = array('curl_class' => 'NorseTest\CurlNoInterface');
        $ipv = new Norse\IPViking($config);
    }

    /**
     * When the sandbox proxy is selected, the API key is set to the default value; thus
     * the instantiation is identical to the default configuration.
     */
    public function testLoadConfigurationArrayProxySandboxWithoutAPIKey() {
        $config = array('proxy' => 'sandbox');
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));
        $this->_verifyIPVDefaults($ipv);
    }

    /**
     * This test ensures that we can override the API Key when selecting the sandbox proxy.
     */
    public function testLoadConfigurationArrayProxySandbox() {
        $config = array(
            'proxy'   => 'sandbox',
            'api_key' => 'testLoadConfigurationArrayProxySandbox',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://beta.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * When instantiating Norse\IPViking with a proxy other than sandbox, an API Key is
     * required.  This test verifies that the expected exception is thrown.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Missing or invalid API key.  A valid API key must be provided for any proxy other than the sandbox.
     * @expectedExceptionCode    182502
     */
    public function testLoadConfigurationArrayProxyNotSandboxWithoutAPIKey() {
        $config = array('proxy' => 'universal');
        $ipv = new Norse\IPViking($config);
    }

    /**
     * Verify the expected url for the 'universal' proxy.
     */
    public function testLoadConfigurationArrayProxyUniversal() {
        $config = array(
            'proxy'   => 'universal',
            'api_key' => 'testLoadConfigurationArrayProxyUniversal',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * Verify the expected url for the 'northamerica' proxy.
     */
    public function testLoadConfigurationArrayProxyNorthAmerica() {
        $config = array(
            'proxy'   => 'NorthAmerica',
            'api_key' => 'testLoadConfigurationArrayProxyNorthAmerica',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://us.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * Verify the expected url for the 'europe' proxy.
     */
    public function testLoadConfigurationArrayProxyEurope() {
        $config = array(
            'proxy'   => 'EUROPE',
            'api_key' => 'testLoadConfigurationArrayProxyEurope',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://eu.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * Verify the expected url for the 'asiapacific' proxy.
     */
    public function testLoadConfigurationArrayProxyAsiaPacific() {
        $config = array(
            'proxy'   => 'asiaPacific',
            'api_key' => 'testLoadConfigurationArrayProxyAsiaPacific',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://as.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * Verify the expected url for the 'southamerica' proxy.
     */
    public function testLoadConfigurationArrayProxySouthAmerica() {
        $config = array(
            'proxy'   => 'SouthAmerica',
            'api_key' => 'testLoadConfigurationArrayProxySouthAmerica',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals('http://la.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * Verify the expected url for the 'universal' proxy when provided literally.
     */
    public function testLoadConfigurationArrayProxyUniversalByValue() {
        $config = array(
            'proxy'   => 'http://api.ipviking.com/api/',
            'api_key' => 'testLoadConfigurationArrayProxyUniversalByValue',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals($config['proxy'],   $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * Verify that even a very complicated URL is preserved accurately after instantiation
     * of Norse\IPViking.
     */
    public function testLoadConfigurationArrayProxyProcessUrl() {
        $config = array(
            'proxy'   => 'https://user:pass@example.com:443/path?query=val#fragment',
            'api_key' => 'testLoadConfigurationArrayProxyProcessUrl',
        );
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($config));

        $this->assertEquals($config['proxy'],   $ipv->getProxy());
        $this->assertEquals($config['api_key'], $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

    /**
     * When the 'proxy' is provided as a URL, it must be well-formed and parseable.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Proxy value provided is not a valid URL.
     * @expectedExceptionCode    182508
     */
    public function testLoadConfigurationArrayProxyProcessUrlNotUrl() {
        $config = array(
            'proxy'   => 'http:///user@:-9/',
            'api_key' => 'testLoadConfigurationArrayProxyProcessUrlNotUrl',
        );
        $ipv = new Norse\IPViking($config);
    }

    /**
     * The 'proxy' value in the configuration array must be a valid, recognized type.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to process proxy designation, check documentation.
     * @expectedExceptionCode    182501
     */
    public function testLoadConfigurationArrayProxyProcessUrlUnableToProcess() {
        $config = array(
            'proxy'   => array('universal'),
            'api_key' => 'testLoadConfigurationArrayProxyUnableToProcess',
        );
        $ipv = new Norse\IPViking($config);
    }

    /**
     * The 'proxy' value must be a recognized format:  known designation or valid url.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Cannot determine proxy host value, check URL.
     * @expectedExceptionCode    182509
     */
    public function testLoadConfigurationArrayProxyProcessUrlNoHostValue() {
        $config = array(
            'proxy'   => 'notaurl',
            'api_key' => 'testLoadConfigurationArrayProxyProcessUrlNoHostValue',
        );
        $ipv = new Norse\IPViking($config);
    }

    /**
     * The 'ini' file provided must exist.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to locate config file, check path.
     * @expectedExceptionCode    182503
     */
    public function testLoadConfigurationIniNofile() {
        $file = __DIR__ . '/noexist.ini';
        $ipv = new Norse\IPViking($file);
    }

    /**
     * The 'ini' file provided must be readable.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to read config file, check permissions.
     * @expectedExceptionCode    182504
     */
    public function testLoadConfigurationIniNotreadable() {
        $file = __DIR__ . '/notreadable.ini';
        chmod($file, 0000);

        $ipv = new Norse\IPViking($file);
    }

    /**
     * We set the permissions of notreadable.ini back to 644
     */
    public function testHackNotreadablePermissions() {
        $file = __DIR__ . '/notreadable.ini';
        chmod($file, 0644);
    }

    /**
     * The 'ini' file provided must not be a directory.
     *
     * @expectedException        Norse\IPViking\Exception_InvalidConfig
     * @expectedExceptionMessage Unable to locate config file, directory path given.
     * @expectedExceptionCode    182505
     */
    public function testLoadConfigurationIniIsDirectory() {
        $file = __DIR__ . '/';
        $ipv = new Norse\IPViking($file);
    }

    // I do not know know to force 182506, 182507

    /**
     * Verify the values of the config are represented accurately when instantiated in
     * a new Norse\IPViking object.
     */
    public function testLoadConfigurationDefaultIni() {
        $file = __DIR__ . '/default.ini';
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($file));
        $this->_verifyIPVDefaults($ipv);
    }

    /**
     * Verify the values of the default config result in a default Norse\IPViking object.
     */
    public function testLoadConfigurationIni() {
        $file = __DIR__ . '/config.ini';
        $settings = parse_ini_file($file);
        $this->assertInstanceOf('Norse\IPViking', $ipv = new Norse\IPViking($file));

        $this->assertEquals('http://us.api.ipviking.com/api/', $ipv->getProxy());
        $this->assertEquals('asdf', $ipv->getApiKey());
        $this->assertInstanceOf('Norse\IPViking\Curl', $ipv->getCurl());
    }

}
