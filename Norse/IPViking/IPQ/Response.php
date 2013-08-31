<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking IPQ Response data.
 */
class IPQ_Response extends Response {
    protected $_ip;
    protected $_trans_id;
    protected $_client_id;
    protected $_custom_id;
    protected $_method;
    protected $_host;
    protected $_risk_factor;
    protected $_risk_color;
    protected $_risk_name;
    protected $_risk_desc;
    protected $_timestamp;
    protected $_factor_entries_count;

    /* IPQ_IPInfo */
    protected $_ip_info;

    /* IPQ_GeoLoc */
    protected $_geo_loc;

    /* IPQ_Entry[] */
    protected $_entries = array();

    /* IPQ_FactoringReason[] */
    protected $_factoring_reasons = array();

    /**
     * Process the cURL response and attempt to package it into this object.
     */
    public function __construct($curl_response, $curl_info) {
        parent::__construct($curl_response, $curl_info);

        $this->_processCurlResponse($curl_response);
    }

    /**
     * Attempts to decode and process the JSON cURL response.
     */
    protected function _processCurlResponse($curl_response) {
        if (!$decoded = json_decode($curl_response)) {
            if (version_compare(PHP_VERSION, '5.5.0', '<=') && function_exists('json_last_error_msg')) {
                $json_error_msg = json_last_error_msg();
            } else {
                $json_error_msg = 'unavailable';
            }

            throw new Exception_Json('Error decoding json response: ' . var_export(array('response' => $curl_response, 'json_error_msg' => $json_error_msg), true), json_last_error());
        }
        $response = $decoded->response;

        if (isset($response->ip))             $this->setIP($response->ip);
        if (isset($response->transID))        $this->setTransID($response->transID);
        if (isset($response->clientID))       $this->setClientID($response->clientID);
        if (isset($response->customID))       $this->setCustomID($response->customID);
        if (isset($response->method))         $this->setMethod($response->method);
        if (isset($response->host))           $this->setHost($response->host);
        if (isset($response->risk_factor))    $this->setRiskFactor($response->risk_factor);
        if (isset($response->risk_color))     $this->setRiskColor($response->risk_color);
        if (isset($response->risk_name))      $this->setRiskName($response->risk_name);
        if (isset($response->risk_desc))      $this->setRiskDesc($response->risk_desc);
        if (isset($response->timestamp))      $this->setTimestamp($response->timestamp);
        if (isset($response->factor_entries)) $this->setFactorEntriesCount($response->factor_entries);

        if (isset($response->ip_info))        $this->setIPInfo($response->ip_info);
        if (isset($response->geoloc))         $this->setGeoLoc($response->geoloc);
        if (isset($response->entries))        $this->setEntries($response->entries);
        if (isset($response->factoring))      $this->setFactoringReasons($response->factoring);
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

    public function setMethod($method) {
        $this->_method = $method;
    }

    public function getMethod() {
        return $this->_method;
    }

    public function setHost($host) {
        $this->_host = $host;
    }

    public function getHost() {
        return $this->_host;
    }

    public function setRiskFactor($risk_factor) {
        $this->_risk_factor = $risk_factor;
    }

    public function getRiskFactor() {
        return $this->_risk_factor;
    }

    public function setRiskColor($risk_color) {
        $this->_risk_color = $risk_color;
    }

    public function getRiskColor() {
        return $this->_risk_color;
    }

    public function setRiskName($risk_name) {
        $this->_risk_name = $risk_name;
    }

    public function getRiskName() {
        return $this->_risk_name;
    }

    public function setRiskDesc($risk_desc) {
        $this->_risk_desc = $risk_desc;
    }

    public function getRiskDesc() {
        return $this->_risk_desc;
    }

    public function setTimestamp($timestamp) {
        $this->_timestamp = $timestamp;
    }

    public function getTimestamp() {
        return $this->_timestamp;
    }

    public function setFactorEntriesCount($factor_entries) {
        $this->_factor_entries_count = $factor_entries;
    }

    public function getFactorEntriesCount() {
        return $this->_factor_entries_count;
    }

    public function setIPInfo($ip_info) {
        $this->_ip_info = new IPQ_IPInfo($ip_info);
    }

    /* IPQ_IPInfo */
    public function getIPInfo() {
        return $this->_ip_info;
    }

    public function setGeoLoc($geoloc) {
        $this->_geo_loc = new IPQ_GeoLoc($geoloc);
    }

    /* IPQ_GeoLoc */
    public function getGeoLoc() {
        return $this->_geo_loc;
    }

    public function setEntries($entries) {
        if (empty($entries) || !is_array($entries)) return;
        foreach ($entries as $entry) {
            $this->_entries[] = new IPQ_Entry($entry);
        }
    }

    /* IPQ_Entry[] */
    public function getEntries() {
        return $this->_entries;
    }

    public function setFactoringReasons($factoring_reasons) {
        if (empty($factoring_reasons) || !is_array($factoring_reasons)) return;

        foreach ($factoring_reasons as $reason) {
            $this->_factoring_reasons[] = new IPQ_FactoringReason($reason);
        }
    }

    /* IPQ_FactoringReason[] */
    public function getFactoringReasons() {
        return $this->_factoring_reasons;
    }

}
