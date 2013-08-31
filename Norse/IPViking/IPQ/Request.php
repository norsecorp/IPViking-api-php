<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking IPQ Request data.
 */
class IPQ_Request extends Request {
    protected $_ip;
    protected $_trans_id;
    protected $_client_id;
    protected $_custom_id;
    protected $_categories;
    protected $_options;
    protected $_address;
    protected $_city;
    protected $_zip;
    protected $_state;
    protected $_country;

    public function __construct($config, $ip) {
        parent::__construct($config);

        $this->setIP($ip);
    }


    /**
     * Basic accessor methods.
     */

    public function setIP($ip) {
        $this->_ip = $ip;
    }

    public function getIP() {
        return $this->_ip;
    }

    public function setTransID($trans_id) {
        $this->_trans_id = $trans_id;
    }

    public function getTransID() {
        return $this->_trans_id;
    }

    public function setClientID($client_id) {
        $this->_client_id = $client_id;
    }

    public function getClientID() {
        return $this->_client_id;
    }

    public function setCustomID($custom_id) {
        $this->_custom_id = $custom_id;
    }

    public function getCustomID() {
        return $this->_custom_id;
    }

    public function setCategories(array $categories) {
        $this->_categories = $categories;
    }

    public function getCategories() {
        return $this->_categories;
    }

    public function getCategoriesString() {
        $categories = $this->getCategories();
        if (empty($categories)) return null;
        return implode(',', $categories);
    }

    public function setOptions(array $options) {
        $this->_options = $options;
    }

    public function getOptions() {
        return $this->_options;
    }

    public function getOptionsString() {
        $options = $this->getOptions();
        if (empty($options)) return null;
        return implode('|', $options);
    }

    public function setAddress($address) {
        $this->_address = $address;
    }

    public function getAddress() {
        return $this->_address;
    }

    public function setCity($city) {
        $this->_city = $city;
    }

    public function getCity() {
        return $this->_city;
    }

    public function setZip($zip) {
        $this->_zip = $zip;
    }

    public function getZip() {
        return $this->_zip;
    }

    public function setState($state) {
        $this->_state = $state;
    }

    public function getState() {
        return $this->_state;
    }

    public function setCountry($country) {
        $this->_country = $country;
    }

    public function getCountry() {
        return $this->_country;
    }


    /**
     * cURL configuration and interaction.
     */

    /**
     * @return array An array of key->value pairs to be URL encoded for requests
     */
    protected function _getBodyFields() {
        $body_fields = parent::_getBodyFields();

        $body_fields['method']     = 'ipq';
        $body_fields['ip']         = $this->getIP();
        $body_fields['transID']    = $this->getTransID();
        $body_fields['clientID']   = $this->getClientID();
        $body_fields['customID']   = $this->getCustomID();
        $body_fields['categories'] = $this->getCategoriesString();
        $body_fields['options']    = $this->getOptionsString();
        $body_fields['address']    = $this->getAddress();
        $body_fields['city']       = $this->getCity();
        $body_fields['zip']        = $this->getZip();
        $body_fields['state']      = $this->getState();
        $body_fields['country']    = $this->getCountry();

        return $body_fields;
    }

    /**
     * @return array An array of CURLOPT->value pairs for cURL configuration.
     */
    protected function _getCurlOpts() {
        $curl_opts = parent::_getCurlOpts();

        $curl_opts[CURLOPT_POST]       = true;
        $curl_opts[CURLOPT_POSTFIELDS] = $this->_getEncodedBody();
        $curl_opts[CURLOPT_HTTPHEADER] = $this->_getHttpHeader();

        return $curl_opts;
    }

    /**
     * A wrapper for curl_exec() which packages the response in a IPQ_Response object.
     *
     * @return IPQ_Response A response object representing the IPQ response.
     */
    public function process() {
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_curlInfo();

        return new IPQ_Response($curl_response, $curl_info);
    }

}
