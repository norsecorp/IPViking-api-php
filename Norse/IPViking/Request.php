<?php

namespace Norse\IPViking;

/**
 * Base object managing the interface with the IPViking API.
 */
class Request {
    /* Target URL for IPViking's API */
    protected $_proxy;

    /* The HTTP Method to be attempted */
    protected $_http_method;

    /* The user's API Key for the IPViking API */
    protected $_api_key;

    /* An instance of CurlInterface */
    protected $_curl;

    /* The response format requested {xml/json} */
    protected $_format;

    /**
     * The constructor sets up key elements of the connection the IPViking API.
     *
     * @param array $config Configuration values for proxy, api_key, and curl.
     *
     * @throws Exception_InvalidRequest:182534 when the supplied config is missing a proxy URL value.
     * @throws Exception_InvalidRequest:182535 when the supplied config is missing an API Key value.
     * @throws Exception_InvalidRequest:182536 when the supplied config is missing a cURL object.
     */
    public function __construct(array $config) {
        if (isset($config['proxy'])) {
            $proxy = $config['proxy'];
        } else {
            throw new Exception_InvalidRequest('Proxy URL required for Request class functionality.', 182534);
        }

        if (isset($config['api_key'])) {
            $api_key = $config['api_key'];
        } else {
            throw new Exception_InvalidRequest('API Key required for Request class functionality.', 182535);
        }

        if (isset($config['curl'])) {
            $curl = $config['curl'];
        } else {
            throw new Exception_InvalidRequest('cURL object required for Request class functionality.', 182536);
        }

        $this->setProxy($proxy);
        $this->setApiKey($api_key);
        $this->setCurl($curl);
    }


    /**
     * Basic accessor methods.
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
     * @throws Exception_Curl:182511 when provided $curl object is not an instance of CurlInterface
     * @throws Exception_Curl:182512 when $curl object fails to initialize
     */
    public function setCurl($curl) {
        if (!$curl instanceof CurlInterface) {
            throw new Exception_Curl('cURL object provided does not implement \Norse\IPViking\CurlInterface.', 182511);
        }

        $this->_curl = $curl;

        if (!$ch = $this->_curl->init($this->getProxy())) {
            throw new Exception_Curl('cURL init failed with URL: ' . $this->getProxy(), 182512);
        }
    }

    public function setFormat($format) {
        $this->_format = $format;
    }

    public function getFormat() {
        return $this->_format;
    }


    /**
     * IPViking supports either 'xml' or 'json' for the response format.  The default value
     * is json.
     */
    protected function _getAcceptFormat() {
        if ($this->getFormat() === 'xml') {
            return 'Accept: application/xml';
        }

        return 'Accept: application/json';
    }

    /**
     * The API Key is always required for API Requests; sub-classes include other key->value
     * pairs.
     */
    protected function _getBodyFields() {
        return array(
            'apikey' => $this->getApiKey(),
        );
    }

    /**
     * The IPViking API expects data to be sent as HTML query string encoded values.
     *
     * @return string An HTML query string encoded string of key->value pairs.
     */
    protected function _getEncodedBody() {
        $body_fields = $this->_getBodyFields();
        return http_build_query($this->_getBodyFields(), '', '&');
    }

    /**
     * The IPViking API expects the following headers:
     *     Content-Type: application/x-www-form-urlencoded
     *     Accept: application/{xml,json}
     *
     * @return array An array of HTTP headers.
     */
    protected function _getHttpHeader() {
        return array(
            'Content-Type:  application/x-www-form-urlencoded',
            $this->_getAcceptFormat(),
        );
    }

    /**
     * Provides base curl configuration values.
     *
     * @return array An array of CURLOPT key->value pairs.
     */
    protected function _getCurlOpts() {
        return array(
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_RETURNTRANSFER => true,
        );
    }

    /**
     *  The following methods wrap the cURL object for setting configuration values and executing.
     */

    protected function _setCurlOpts() {
        $this->_setCurlOptArray($this->_getCurlOpts());
    }

    /**
     * @throws Exception_Curl: when curl_setopt() fails.
     */
    protected function _setCurlOpt($option, $value) {
        if (!$this->_curl->setOpt($option, $value)) {
            throw new Exception_Curl('cURL setopt failed with error: ' . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
        }
    }

    /**
     * @throws Exception_Curl: when curl_setopt_array() fails.
     */
    protected function _setCurlOptArray($options) {
        if (!$this->_curl->setOptArray($options)) {
            throw new Exception_Curl("cURL setopt array failed with error: " . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
        }
    }

    /**
     * @return mixed The result of curl_init().
     */
    public function exec() {
        $this->_setCurlOpts();
        return $this->_curlExec();
    }

    /**
     * @return array The array of values returned by curl_getinfo()
     *
     * @throws Exception_Curl: when curl_getinfo() fails.
     */
    protected function _curlInfo() {
        $info = $this->_curl->getInfo();

        if (false === $info) {
            throw new Exception_Curl('cURL getinfo failed with error: ' . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
        }

        return $info;
    }

    /**
     * @return mixed The result of curl_exec()
     *
     * @throws Exception_Curl: when curl_exec() fails.
     */
    protected function _curlExec() {
        $result = $this->_curl->exec();

        if (false === $result) {
            throw new Exception_Curl('cURL exec failed with error: ' . $this->_curl->getLastError(), $this->_curl->getLastErrorNo());
        }

        return $result;
    }

}
