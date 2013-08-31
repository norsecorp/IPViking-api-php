<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking GeoFilter Settings data.
 */
class Settings_GeoFilter_Filter {
    protected $_filter_id;
    protected $_command;
    protected $_client_id;
    protected $_action;
    protected $_category;
    protected $_country = '-';
    protected $_region  = '-';
    protected $_city    = '-';
    protected $_zip     = '-';
    protected $_hits    = 0;

    /**
     * The constructor accepts either an object or an array of data.
     *
     * @param mixed $filter An object or array encapsulating GeoFilter data.
     *
     * @throws Exception_InvalidGeoFilter:182580 when the provided argument is neither an object nor an array.
     */
    public function __construct($filter = null) {
        if (!empty($filter)) {
            if (is_object($filter)) {
                $this->_processObject($filter);
            } elseif (is_array($filter)) {
                $this->_processArray($filter);
            } else {
                throw new Exception_InvalidGeoFilter('Unexpected format for instantiation of Norse\IPViking\Settings_GeoFilter_Filter object.', 182580);
            }
        }
    }

    protected function _processObject($filter) {
        if (isset($filter->filter_id)) $this->setFilterID($filter->filter_id);
        if (isset($filter->command))   $this->setCommand($filter->command);
        if (isset($filter->clientID))  $this->setClientID($filter->clientID);
        if (isset($filter->action))    $this->setAction($filter->action);
        if (isset($filter->category))  $this->setCategory($filter->category);
        if (isset($filter->country))   $this->setCountry($filter->country);
        if (isset($filter->region))    $this->setRegion($filter->region);
        if (isset($filter->city))      $this->setCity($filter->city);
        if (isset($filter->zip))       $this->setZip($filter->zip);
        if (isset($filter->hits))      $this->setHits($filter->hits);
    }

    protected function _processArray($filter) {
        if (isset($filter['filter_id'])) $this->setFilterID($filter['filter_id']);
        if (isset($filter['command']))   $this->setCommand($filter['command']);
        if (isset($filter['client_id'])) $this->setClientID($filter['client_id']);
        if (isset($filter['action']))    $this->setAction($filter['action']);
        if (isset($filter['category']))  $this->setCategory($filter['category']);
        if (isset($filter['country']))   $this->setCountry($filter['country']);
        if (isset($filter['region']))    $this->setRegion($filter['region']);
        if (isset($filter['city']))      $this->setCity($filter['city']);
        if (isset($filter['zip']))       $this->setZip($filter['zip']);
        if (isset($filter['hits']))      $this->setHits($filter['hits']);
    }


    /**
     * Basic accessor methods.
     */

    public function setFilterID($filter_id) {
        $this->_filter_id = $filter_id;
    }

    public function getFilterID() {
        return $this->_filter_id;
    }

    public function setCommand($command) {
        $command = strtolower($command);
        if ($command !== 'add' && $command !== 'delete') {
            throw new Exception_InvalidGeoFilter('Invalid value for Settings_GeoFilter_Filter::command; expecting \'add\' or \'delete\', given '. var_export($command, true), 182589);
        }

        $this->_command = $command;
    }

    public function getCommand() {
        return $this->_command;
    }

    public function setClientID($client_id) {
        if (!is_numeric($client_id) || $client_id < 0) {
            throw new Exception_InvalidGeoFilter('Invalid value for Settings_GeoFilter_Filter::client_id; expecting int > 0, given ' . var_export($client_id, true), 182584);
        }

        $this->_client_id = $client_id;
    }

    public function getClientID() {
        return $this->_client_id;
    }

    public function setAction($action) {
        $action = strtolower($action);
        if ($action !== 'allow' && $action !== 'deny') {
            throw new Exception_InvalidGeoFilter('Invalid value for Settings_GeoFilter_Filter::action; expecting \'allow\' or \'deny\', given '. var_export($action, true), 182585);
        }

        $this->_action = $action;
    }

    public function getAction() {
        return $this->_action;
    }

    public function setCategory($category) {
        $category = strtolower($category);
        if ($category !== 'master' && $category !== 'zip' && $category !== 'city' && $category !== 'region' && $category !== 'country') {
            throw new Exception_InvalidGeoFilter('Invalid value for Settings_GeoFilter_Filter::category; expecting \'master\', \'zip\', \'city\', \'region\' or \'country\', given '. var_export($category, true), 182586);
        }

        $this->_category = $category;
    }

    public function getCategory() {
        return $this->_category;
    }

    public function setCountry($country) {
        if (!preg_match('/^\w{2}$/', $country) && $country !== '-') {
            throw new Exception_InvalidGeoFilter('Invalid value for Settings_GeoFilter_Filter::country; expecting char(2), given '. var_export($country, true), 182587);
        }

        $this->_country = $country;
    }

    public function getCountry() {
        return $this->_country;
    }

    public function setRegion($region) {
        $this->_region = $region;
    }

    public function getRegion() {
        return $this->_region;
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

    public function setHits($hits) {
        $this->_hits = $hits;
    }

    public function getHits() {
        return $this->_hits;
    }

}
