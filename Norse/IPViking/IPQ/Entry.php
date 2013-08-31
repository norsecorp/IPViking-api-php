<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking IPQ data.
 */
class IPQ_Entry {
    protected $_category_id;
    protected $_category_name;
    protected $_category_factor;
    protected $_protocol_id;
    protected $_protocol_name;
    protected $_overall_protocol;
    protected $_last_active;

    public function __construct($entry) {
        if (isset($entry->category_id))      $this->setCategoryID($entry->category_id);
        if (isset($entry->category_name))    $this->setCategoryName($entry->category_name);
        if (isset($entry->category_factor))  $this->setCategoryFactor($entry->category_factor);
        if (isset($entry->protocol_id))      $this->setProtocolID($entry->protocol_id);
        if (isset($entry->protocol_name))    $this->setProtocolName($entry->protocol_name);
        if (isset($entry->overall_protocol)) $this->setOverallProtocol($entry->overall_protocol);
        if (isset($entry->last_active))      $this->setLastActive($entry->last_active);
    }


    /**
     * Basic accessor methods.
     */

    public function setCategoryID($category_id) {
        $this->_category_id = $category_id;
    }

    public function getCategoryID() {
        return $this->_category_id;
    }

    public function setCategoryName($category_name) {
        $this->_category_name = $category_name;
    }

    public function getCategoryName() {
        return $this->_category_name;
    }

    public function setCategoryFactor($category_factor) {
        $this->_category_factor = $category_factor;
    }

    public function getCategoryFactor() {
        return $this->_category_factor;
    }

    public function setProtocolID($protocol_id) {
        $this->_protocol_id = $protocol_id;
    }

    public function getProtocolID() {
        return $this->_protocol_id;
    }

    public function setProtocolName($protocol_name) {
        $this->_protocol_name = $protocol_name;
    }

    public function getProtocolName() {
        return $this->_protocol_name;
    }

    public function setOverallProtocol($overall_protocol) {
        $this->_overall_protocol = $overall_protocol;
    }

    public function getOverallProtocol() {
        return $this->_overall_protocol;
    }

    public function setLastActive($last_active) {
        $this->_last_active = $last_active;
    }

    public function getLastActive() {
        return $this->_last_active;
    }

}
