<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking GeoLoc data.
 */
class IPQ_GeoLoc {
	protected $_country;
	protected $_country_code;
	protected $_region;
	protected $_region_code;
	protected $_city;
	protected $_latitude;
	protected $_longitude;
	protected $_internet_service_provider;
	protected $_organization;

    public function __construct($geoLoc) {
        if (isset($geoLoc->country))                   $this->setCountry($geoLoc->country);
        if (isset($geoLoc->country_code))              $this->setCountryCode($geoLoc->country_code);
        if (isset($geoLoc->region))                    $this->setRegion($geoLoc->region);
        if (isset($geoLoc->region_code))               $this->setRegionCode($geoLoc->region_code);
        if (isset($geoLoc->city))                      $this->setCity($geoLoc->city);
        if (isset($geoLoc->latitude))                  $this->setLatitude($geoLoc->latitude);
        if (isset($geoLoc->longtitude))                $this->setLongitude($geoLoc->longtitude);
        if (isset($geoLoc->internet_service_provider)) $this->setInternetServiceProvider($geoLoc->internet_service_provider);
        if (isset($geoLoc->organization))              $this->setOrganization($geoLoc->organization);
    }


    /**
     * Basic accessor methods.
     */

    public function setCountry($country) {
        $this->_country = $country;
    }

    public function getCountry() {
        return $this->_country;
    }

    public function setCountryCode($country_code) {
        $this->_country_code = $country_code;
    }

    public function getCountryCode() {
        return $this->_country_code;
    }

    public function setRegion($region) {
        $this->_region = $region;
    }

    public function getRegion() {
        return $this->_region;
    }

    public function setRegionCode($region_code) {
        $this->_region_code = $region_code;
    }

    public function getRegionCode() {
        return $this->_region_code;
    }

    public function setCity($city) {
        $this->_city = $city;
    }

    public function getCity() {
        return $this->_city;
    }

    public function setLatitude($latitude) {
        $this->_latitude = $latitude;
    }

    public function getLatitude() {
        return $this->_latitude;
    }

    public function setLongitude($longitude) {
        $this->_longitude = $longitude;
    }

    public function getLongitude() {
        return $this->_longitude;
    }

    public function setInternetServiceProvider($internet_service_provider) {
        $this->_internet_service_provider = $internet_service_provider;
    }

    public function getInternetServiceProvider() {
        return $this->_internet_service_provider;
    }

    public function setOrganization($organization) {
        $this->_organization = $organization;
    }

    public function getOrganization() {
        return $this->_organization;
    }

}
