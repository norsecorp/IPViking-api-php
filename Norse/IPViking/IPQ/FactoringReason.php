<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking FactoringReason data.
 */
class IPQ_FactoringReason {
    protected $_country_risk_factor;
    protected $_region_risk_factor;
    protected $_ip_resolve_factor;
    protected $_asn_record_factor;
    protected $_asn_threat_factor;
    protected $_bgp_delegation_factor;
    protected $_iana_allocation_factor;
    protected $_ipviking_personal_factor;
    protected $_ipviking_category_factor;
    protected $_ipviking_geofilter_factor;
    protected $_ipviking_geofilter_rule;
    protected $_geomatch_distance;
    protected $_geomatch_factor;
    protected $_data_age_factor;
    protected $_search_volume_factor;

    public function __construct($reason) {
        if (isset($reason->country_risk_factor))       $this->setCountryRiskFactor($reason->country_risk_factor);
        if (isset($reason->region_risk_factor))        $this->setRegionRiskFactor($reason->region_risk_factor);
        if (isset($reason->ip_resolve_factor))         $this->setIPResolveFactor($reason->ip_resolve_factor);
        if (isset($reason->asn_record_factor))         $this->setAsnRecordFactor($reason->asn_record_factor);
        if (isset($reason->asn_threat_factor))         $this->setAsnThreatFactor($reason->asn_threat_factor);
        if (isset($reason->bgp_delegation_factor))     $this->setBgpDelegationFactor($reason->bgp_delegation_factor);
        if (isset($reason->iana_allocation_factor))    $this->setIanaAllocationFactor($reason->iana_allocation_factor);
        if (isset($reason->ipviking_personal_factor))  $this->setIPVikingPersonalFactor($reason->ipviking_personal_factor);
        if (isset($reason->ipviking_category_factor))  $this->setIPVikingCategoryFactor($reason->ipviking_category_factor);
        if (isset($reason->ipviking_geofilter_factor)) $this->setIPVikingGeoFilterFactor($reason->ipviking_geofilter_factor);
        if (isset($reason->ipviking_geofilter_rule))   $this->setIPVikingGeoFilterRule($reason->ipviking_geofilter_rule);
        if (isset($reason->geomatch_distance))         $this->setGeoMatchDistance($reason->geomatch_distance);
        if (isset($reason->geomatch_factor))           $this->setGeoMatchFactor($reason->geomatch_factor);
        if (isset($reason->data_age_factor))           $this->setDataAgeFactor($reason->data_age_factor);
        if (isset($reason->search_volume_factor))      $this->setSearchVolumeFactor($reason->search_volume_factor);
    }


    /**
     * Basic accessor methods.
     */

    public function setCountryRiskFactor($country_risk_factor) {
        $this->_country_risk_factor = $country_risk_factor;
    }

    public function getCountryRiskFactor() {
        return $this->_country_risk_factor;
    }

    public function setRegionRiskFactor($region_risk_factor) {
        $this->_region_risk_factor = $region_risk_factor;
    }

    public function getRegionRiskFactor() {
        return $this->_region_risk_factor;
    }

    public function setIPResolveFactor($ip_resolve_factor) {
        $this->_ip_resolve_factor = $ip_resolve_factor;
    }

    public function getIPResolveFactor() {
        return $this->_ip_resolve_factor;
    }

    public function setAsnRecordFactor($asn_record_factor) {
        $this->_asn_record_factor = $asn_record_factor;
    }

    public function getAsnRecordFactor() {
        return $this->_asn_record_factor;
    }

    public function setAsnThreatFactor($asn_threat_factor) {
        $this->_asn_threat_factor = $asn_threat_factor;
    }

    public function getAsnThreatFactor() {
        return $this->_asn_threat_factor;
    }

    public function setBgpDelegationFactor($bgp_delegation_factor) {
        $this->_bgp_delegation_factor = $bgp_delegation_factor;
    }

    public function getBgpDelegationFactor() {
        return $this->_bgp_delegation_factor;
    }

    public function setIanaAllocationFactor($iana_allocation_factor) {
        $this->_iana_allocation_factor = $iana_allocation_factor;
    }

    public function getIanaAllocationFactor() {
        return $this->_iana_allocation_factor;
    }

    public function setIPVikingPersonalFactor($ipviking_personal_factor) {
        $this->_ipviking_personal_factor = $ipviking_personal_factor;
    }

    public function getIPVikingPersonalFactor() {
        return $this->_ipviking_personal_factor;
    }

    public function setIPVikingCategoryFactor($ipviking_category_factor) {
        $this->_ipviking_category_factor = $ipviking_category_factor;
    }

    public function getIPVikingCategoryFactor() {
        return $this->_ipviking_category_factor;
    }

    public function setIPVikingGeoFilterFactor($ipviking_geofilter_factor) {
        $this->_ipviking_geofilter_factor = $ipviking_geofilter_factor;
    }

    public function getIPVikingGeoFilterFactor() {
        return $this->_ipviking_geofilter_factor;
    }

    public function setIPVikingGeoFilterRule($ipviking_geofilter_rule) {
        $this->_ipviking_geofilter_rule = $ipviking_geofilter_rule;
    }

    public function getIPVikingGeoFilterRule() {
        return $this->_ipviking_geofilter_rule;
    }

    public function setGeoMatchDistance($geomatch_distance) {
        $this->_geomatch_distance = $geomatch_distance;
    }

    public function getGeoMatchDistance() {
        return $this->_geomatch_distance;
    }

    public function setGeoMatchFactor($geomatch_factor) {
        $this->_geomatch_factor = $geomatch_factor;
    }

    public function getGeoMatchFactor() {
        return $this->_geomatch_factor;
    }

    public function setDataAgeFactor($data_age_factor) {
        $this->_data_age_factor = $data_age_factor;
    }

    public function getDataAgeFactor() {
        return $this->_data_age_factor;
    }

    public function setSearchVolumeFactor($search_volume_factor) {
        $this->_search_volume_factor = $search_volume_factor;
    }

    public function getSearchVolumeFactor() {
        return $this->_search_volume_factor;
    }

}
