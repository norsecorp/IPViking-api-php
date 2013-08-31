<?php

namespace Norse;

class IPViking {

    /* API Proxies */
    const PROXY_UNIVERSAL    = 'http://api.ipviking.com/api/';
    const PROXY_NORTHAMERICA = 'http://us.api.ipviking.com/api/';
    const PROXY_EUROPE       = 'http://eu.api.ipviking.com/api/';
    const PROXY_ASIAPACIFIC  = 'http://as.api.ipviking.com/api/';
    const PROXY_SOUTHAMERICA = 'http://la.api.ipviking.com/api/';
    const PROXY_SANDBOX      = 'http://beta.ipviking.com/api/';

    /* Key for Sandbox Proxy */
    const SANDBOX_API_KEY = '8292777557e8eb8bc169c2af29e87ac07d0f1ac4857048044402dbee06ba5cea';

    /* One of the API proxy constants defined above */
    protected $_proxy;

    /**
     * @param string $_api_key
     */
    protected $_api_key;

    /**
     * @param string $_curl
     */
    protected $_curl;

    /**
     * Instantiate and configure the IPViking object given.  The argument may be either
     * an array of values, a string representing the path to a configuration file, or
     * null.
     *
     * @param array $config Accepted values include
     *      'proxy'
     *      'api_key'
     *      'curl_class'
     *
     * @throws IPViking\Exception_InvalidConfig:182500 when $config argument is not {null, string, array}
     */
    public function __construct($config = null) {
        if (is_array($config)) {
            $this->_loadConfigFromArray($config);
        } elseif (is_string($config)) {
            $this->_loadConfigFromFile($config);
        } elseif (is_null($config)) {
            $this->_loadConfigDefaults();
        } else {
            throw new IPViking\Exception_InvalidConfig('Unable to determine format of provided configuration.', 182500);
        }
    }

    /**
     * Load the default settings:  Sandbox Proxy, Sandbox API Key, Norse\IPViking\Curl
     */
    protected function _loadConfigDefaults() {
        $this->setProxy(self::PROXY_SANDBOX);
        $this->setApiKey(self::SANDBOX_API_KEY);
        $this->setCurl('Norse\IPViking\Curl');
    }

    /**
     * Loads configuration values from an array.
     *
     * @param array $config An array of parameters containing one or more of: {proxy, api_key, curl_class}
     *
     * @throws IPViking\Exception_InvalidConfig:182501 when proxy value is set but is not a string.
     * @throws IPViking\Exception_InvalidConfig:182502 when api_key is not set and proxy is not sandbox.
     */
    protected function _loadConfigFromArray(array $config) {
        // Set defaults for any values which may be missing
        $this->_loadConfigDefaults();

        // default proxy is sandbox
        if (!empty($config['proxy'])) {
            $predefined_proxies = $this->getPredefinedProxies();
            if (is_string($config['proxy'])) {
                if (isset($predefined_proxies[strtolower($config['proxy'])])) {
                    $this->setProxy($predefined_proxies[strtolower($config['proxy'])]);
                } elseif (in_array($config['proxy'], $predefined_proxies)) {
                    $this->setProxy($config['proxy']);
                } else {
                    $this->setProxy($this->_processUrl($config['proxy']));
                }
            } else {
                throw new IPViking\Exception_InvalidConfig('Unable to process proxy designation, check documentation.', 182501);
            }
        }

        // default api key available for sandbox proxy only
        if (!empty($config['api_key'])) {
            $this->setApiKey($config['api_key']);
        } elseif ($this->getProxy() == self::PROXY_SANDBOX) {
            $this->setApiKey(self::SANDBOX_API_KEY);
        } else {
            throw new IPViking\Exception_InvalidConfig('Missing or invalid API key.  A valid API key must be provided for any proxy other than the sandbox.', 182502);
        }

        // default curl class is Norse\IPViking\Curl
        if (!empty($config['curl_class'])) {
            $this->setCurl($config['curl_class']);
        }

    }

    /**
     * Parse the given file as an ini to create an array consumable by _loadConfigFromArray.
     *
     * @param string $file A relative of absolute path to an .ini file contaning configuration data usable by _loadConfigFromArray()
     *
     * @throws IPViking\Exception_InvalidConfig:182503 when config file does not exist.
     * @throws IPViking\Exception_InvalidConfig:182504 when config file is not readable.
     * @throws IPViking\Exception_InvalidConfig:182505 when config file appears to be a directory or other non-file type.
     * @throws IPViking\Exception_InvalidConfig:182506 when config file cannot be parsed by parse_ini_file().
     * @throws IPViking\Exception_InvalidConfig:182507 when config file does not result in an array.
     */
    protected function _loadConfigFromFile($file) {
        if (!file_exists($file)) {
            throw new IPViking\Exception_InvalidConfig('Unable to locate config file, check path.', 182503);
        }

        if (!is_readable($file)) {
            throw new IPViking\Exception_InvalidConfig('Unable to read config file, check permissions.', 182504);
        }

        if (!is_file($file)) {
            throw new IPViking\Exception_InvalidConfig('Unable to locate config file, directory path given.', 182505);
        }

        $config = parse_ini_file($file);

        if (false === $config) {
            throw new IPViking\Exception_InvalidConfig('Unable to parse config file, ensure it is a valid .ini', 182506);
        }

        if (!is_array($config)) {
            throw new IPViking\Exception_InvalidConfig('Unable to parse config file, ensure it is a valid .ini', 182507);
        }

        $this->_loadConfigFromArray($config);
    }

    /**
     * Attempt to validate the given string is a URL.
     *
     * @param string $str A candidate URL.
     *
     * @return string A validated URL.
     *
     * @throws IPViking\Exception_InvalidConfig:182508 when parse_url() fails to handle provided value.
     * @throws IPViking\Exception_InvalidConfig:182509 when parsed URL has no identifiable host value.
     */
    protected function _processUrl($str) {
        // if parse_url can't handle it, it's probably not a valid url
        if (!$url = parse_url($str)) {
            throw new IPViking\Exception_InvalidConfig('Proxy value provided is not a valid URL.', 182508);
        }

        // ensure that we have at least a host value
        if (!isset($url['host'])) {
            throw new IPViking\Exception_InvalidConfig('Cannot determine proxy host value, check URL.', 182509);
        }

        return (
            ((isset($url['scheme'])) ? $url['scheme'] : 'http' ) .
            '://' .
            ((isset($url['user'])) ? $url['user'] : '' ) .
            ((isset($url['user'], $url['pass'])) ? ':' : '' ) .
            ((isset($url['pass'])) ? $url['pass'] : '' ) .
            ((isset($url['user']) || isset($url['pass'])) ? '@' : '' ) .
            $url['host'] .
            ((isset($url['port'])) ? ':' . $url['port'] : '' ) .
            ((isset($url['path'])) ? $url['path'] : '' ) .
            ((isset($url['query'])) ? '?' . $url['query'] : '' ) .
            ((isset($url['fragment'])) ? '#' . $url['fragment'] : '' )
        );
    }

    /**
     * This method returns all pre-defined proxy values to allow configuration by name.
     *
     * @return array An array of pre-defined proxy values.
     */
    public function getPredefinedProxies() {
        return array(
            'universal'    => self::PROXY_UNIVERSAL,
            'northamerica' => self::PROXY_NORTHAMERICA,
            'europe'       => self::PROXY_EUROPE,
            'asiapacific'  => self::PROXY_ASIAPACIFIC,
            'southamerica' => self::PROXY_SOUTHAMERICA,
            'sandbox'      => self::PROXY_SANDBOX,
        );
    }


    /**
     * Accessor methods
     */

    public function setProxy($proxy) {
        $this->_proxy = $proxy;
    }

    public function getProxy() {
        return $this->_proxy;
    }

    public function setApiKey($api_key) {
        $this->_api_key = $api_key;
    }

    public function getApiKey() {
        return $this->_api_key;
    }

    /**
     * @throws IPViking\Exception_InvalidConfig:182500 when curl_class instantiation does not implement IPViking\CurlInterface.
     */
    public function setCurl($class) {
        $curl = new $class();

        if (!$curl instanceof IPViking\CurlInterface) {
            throw new IPViking\Exception_InvalidConfig('Curl class must implement Norse\IPViking\CurlInterface.', 182500);
        }

        $this->_curl = $curl;
    }

    public function getCurl() {
        return $this->_curl;
    }

    /**
     * Returns an array of the configuration settings of this class for use by the curl class.
     */
    public function getConfig() {
        return array(
            'proxy'   => $this->getProxy(),
            'api_key' => $this->getApiKey(),
            'curl'    => $this->getCurl(),
        );
    }


    /**
     * IP Validation methods.
     */

    /**
     * A wrapper function to support both IPv4 and IPv6 IPs.
     * NOTE: IPViking currently only offers support for IPv4
     *
     * @param string $ip A candidate IP address.
     *
     * @return bool TRUE if the IP seems it be a valid IPv4 or IPv6 address, FALSE otherwise.
     */
    protected function _validateIP($ip) {
        return $this->_validateIPv4($ip) || $this->_validateIPv6($ip);
    }

    /**
     * Uses a regular expression comparison to attempt to validate a candidate IPv4 address.
     *
     * @param string $ip A candidate IPv4 address.
     *
     * @return bool TRUE if the IP seems to be a valid IPv4 address, FALSE otherwise.
     */
    protected function _validateIPv4($ip) {
        return preg_match('/^(([01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}([01]?\d{1,2}|2[0-4]\d|25[0-5])/', $ip);
    }

    /**
     * NOTE:  This method has not yet been implemented as IPViking does not currently support IPv6 IP addresses.
     *
     * @param string $ip A candidate IPv6 address.
     *
     * @return bool FALSE This method always fails as IPv6 is not currently supported.
     */
    protected function _validateIPv6($ip) {
        return false;
    }


    /**
     * IPViking IPQ Endpoint Methods.
     */

    /**
     * A basic wrapper for the IPQ endpoint which accepts an IP address as an argument and returns an IPViking\IPQ_Response object.
     *
     * @param string $ip A candidate IP address about which additional information is requested.
     *
     * @return IPViking\IPQ_Response An object encapsulating the data of the IPQ response.
     *
     * @throws IPViking\Exception_InvalidRequestL182530 when the supplied IP address does not seem to be valid.
     */
    public function ipq($ip) {
        if (!$this->_validateIP($ip)) {
            throw new IPViking\Exception_InvalidRequest('The IP provided is not a valid IP address.', 182530);
        }

        $ipq = new IPViking\IPQ_Request($this->getConfig(), $ip);
        return $ipq->process();
    }

    /**
     * Retreives a fresh instance of an IPViking\IPQ_Request object for advanced use.
     *
     * @param string $ip A candidate IP address about which additional information will be requested.
     *
     * @return IPViking\IPQ_Request An object representing a request to the IPQ endpoint of the IPViking API.
     *
     * @throws IPViking_Exception_InvalidRequest:182531 when the supplied IP address does not seem to be valid.
     */
    public function getIPQRequest($ip) {
        if (!$this->_validateIP($ip)) {
            throw new IPViking\Exception_InvalidRequest('The IP provided is not a valid IP address.', 182531);
        }
        return new IPViking\IPQ_Request($this->getConfig(), $ip);
    }

    /**
     * A basic wrapper for the IPQ endpoint which accepts an IP address as an argument and returns the raw XML response.
     *
     * @param string $ip A candidate IP address about which additional information is requested.
     *
     * @return string application/xml encoded response from the IPQ endpoint of the IPViking API.
     *
     * @throws IPViking\Exception_InvalidRequest:182532 when the supplied IP address does not seem to be valid.
     */
    public function xml($ip) {
        if (!$this->_validateIP($ip)) {
            throw new IPViking\Exception_InvalidRequest('The IP provided is not a valid IP address.', 182532);
        }

        $ipq = new IPViking\IPQ_Request($this->getConfig(), $ip);
        $ipq->setFormat('xml');
        return $ipq->exec();
    }


    /**
     * IPViking Submission Endpoint Methods.
     */

    /**
     * A wrapper for the Submission endpoint of the IPViking API.  It accepts {ip, protocol, category, timestamp}
     * and attempts to make a PUT request to the configured IPViking Proxy with that information.
     *
     * @param string $ip A candidate IP address about which information will be provided to the IPViking server.
     * @param int $protocol The integer value of the protocol describing the given IP activity.
     * @param int $category The integer value of the category describing the given IP activity.
     * @param timestamp $timestamp A 13-digit timestamp indicating the most recent occurence of the defined activity for the given IP.
     *
     * @return IPViking\Submission_Response An object representing the response from the IPViking server.
     *
     * @throws IPViking\Exception_InvalidRequest:182533 when the supplied IP address does not seem to be valid.
     *
     * @see IPViking documentation for up-to-date values for `protocol` and `category`.
     */
    public function submission($ip, $protocol, $category, $timestamp) {
        if (!$this->_validateIP($ip)) {
            throw new IPViking\Exception_InvalidRequest('The IP provided is not a valid IP address.', 182533);
        }

        $submission = new IPViking\Submission_Request($this->getConfig(), $ip, $protocol, $category, $timestamp);
        return $submission->process();
    }


    /**
     * IPViking GeoFilter Settings Endpoint Methods.
     */

    /**
     * Retreive current GeoFilter settings.
     *
     * @return IPViking\Settings_GeoFilter_Collection The current GeoFilter settings.
     */
    public function getGeoFilterSettings() {
        $geofilter_settings = new IPViking\Settings_GeoFilter($this->getConfig());
        return $geofilter_settings->getCurrentSettings();
    }

    /**
     * Retreives a fresh instance of an IPViking\Settings_GeoFilter_Filter object for advanced use.
     *
     * @return IPViking\Settings_GeoFilter_Filter An object representing a request to the Settings\GeoFilter endpoint of the IPViking API.
     */
    public function getNewGeoFilter() {
        return new IPViking\Settings_GeoFilter_Filter();
    }

    /**
     * Send a request to add the given GeoFilter to current settings.
     *
     * @param IPViking\Settings_GeoFilter_Filter An object representing the filter to add.
     *
     * @return IPViking\Settings_GeoFilter_Collection The resulting GeoFilter settings.
     */
    public function addGeoFilter(IPViking\Settings_GeoFilter_Filter $filter) {
        $geofilter_settings = new IPViking\Settings_GeoFilter($this->getConfig());
        return $geofilter_settings->addGeoFilter($filter);
    }

    /**
     * Send a request to delete the given GeoFilter from current settings.
     *
     * @param IPViking\Settings_GeoFilter_Filter An object representing the filter to delete.
     *
     * @return IPViking\Settings_GeoFilter_Collection The resulting GeoFilter settings.
     */
    public function deleteGeoFilter(IPViking\Settings_GeoFilter_Filter $filter) {
        $geofilter_settings = new IPViking\Settings_GeoFilter($this->getConfig());
        return $geofilter_settings->deleteGeoFilter($filter);
    }

    /**
     * Send a request to make multiple updates (adding and deleting) to GeoFilter settings.
     *
     * @param array An array of IPViking\Settings_GeoFilter_Filter objects (or representations thereof)
     *
     * @return IPViking\Settings_GeoFilter_Collection The resulting GeoFilter settings.
     */
    public function updateGeoFilters(array $filters) {
        $geofilter_settings = new IPViking\Settings_GeoFilter($this->getConfig());
        return $geofilter_settings->updateGeoFilters($filters);
    }


    /**
     * IPViking RiskFactor Settings Endpoint Methods.
     */

    /**
     * Retreive current RiskFactor settings.
     *
     * @return IPViking\Settings_RiskFactor_Collection The current RiskFactor settings.
     */
    public function getRiskFactorSettings() {
        $riskfactor_settings = new IPViking\Settings_RiskFactor($this->getConfig());
        return $riskfactor_settings->getCurrentSettings();
    }

    /**
     * Retreives a fresh instance of an IPViking\Settings_RiskFactor_Factor object for advanced use.
     *
     * @return IPViking\Settings_RiskFactor_Factor An object representing a request to the Settings\RiskFactor endpoint of the IPViking API.
     */
    public function getNewRiskFactor() {
        return new IPViking\Settings_RiskFactor_Factor();
    }

    /**
     * Send a request to add the given RiskFactor to current settings.
     *
     * @param IPViking\Settings_RiskFactor_Factor An object representing the factor to add.
     *
     * @return IPViking\Settings_RiskFactor_Collection The resulting RiskFactor settings.
     */
    public function addRiskFactor(IPViking\Settings_RiskFactor_Factor $factor) {
        $riskfactor_settings = new IPViking\Settings_RiskFactor($this->getConfig());
        return $riskfactor_settings->addRiskFactor($factor);
    }

    /**
     * Send a request to delete the given RiskFactor from current settings.
     *
     * @param IPViking\Settings_RiskFactor_Factor An object representing the factor to delete.
     *
     * @return IPViking\Settings_RiskFactor_Collection The resulting RiskFactor settings.
     */
    public function deleteRiskFactor(IPViking\Settings_RiskFactor_Factor $factor) {
        $riskfactor_settings = new IPViking\Settings_RiskFactor($this->getConfig());
        return $riskfactor_settings->deleteRiskFactor($factor);
    }

    /**
     * Send a request to make multiple updates (adding and deleting) to RiskFactor settings.
     *
     * @param array An array of IPViking\Settings_RiskFactor_Factor objects (or representations thereof)
     *
     * @return IPViking\Settings_RiskFactor_Collection The resulting RiskFactor settings.
     */
    public function updateRiskFactors(array $factors) {
        $riskfactor_settings = new IPViking\Settings_RiskFactor($this->getConfig());
        return $riskfactor_settings->updateRiskFactors($factors);
    }

}
