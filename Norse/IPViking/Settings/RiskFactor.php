<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking Settings RiskFactor Request data.
 */
class Settings_RiskFactor extends Request {
    protected $_collection;

    public function __construct($config) {
        parent::__construct($config);
    }


    /**
     * Basic accessor methods.
     */

    public function setCollection(Settings_RiskFactor_Collection $collection) {
        $this->_collection = $collection;
    }

    public function getCollection() {
        return $this->_collection;
    }

    /**
     * Retreives XML representation of RiskFactor data.
     *
     * @return null|string XML corresponding to RiskFactor data.
     */
    protected function _getRiskFactorXML() {
        $collection = $this->getCollection();

        if (empty($collection)) return null;
        return $this->getCollection()->getRiskFactorXML();
    }


    /**
     * cURL configuration and interaction.
     */

    /**
     * @return array An array of key->value pairs to be URL encoded for requests
     */
    protected function _getBodyFields() {
        $body_fields = parent::_getBodyFields();

        $body_fields['method']      = 'riskfactor';
        $body_fields['settingsxml'] = $this->_getRiskFactorXML();

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
     * A wrapper for curl_exec() which packages the response in a Settings_RiskFactor_Collection object.
     *
     * @return Settings_RiskFactor_Collection A response collection representing the RiskFactor response.
     */
    public function process() {
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_curlInfo();

        return new Settings_RiskFactor_Collection($curl_response, $curl_info);
    }


    /**
     * API Methods
     */

    /**
     * @return Settings_RiskFactor_Collection
     */
    public function getCurrentSettings() {
        return $this->process();
    }

    /**
     * Adds a given filter to RiskFactor settings.
     *
     * @param Settings_RiskFactor_Factor The factor to be deleted.
     *
     * @return Settings_RiskFactor_Collection
     */
    public function addRiskFactor(Settings_RiskFactor_Factor $filter) {
        $filter->setCommand('add');
        $this->setCollection(new Settings_RiskFactor_Collection(array($filter)));

        return $this->process();
    }

    /**
     * Deletes the given filter from RiskFactor settings.
     *
     * @param Settings_RiskFactor_Factor The factor to be deleted.
     *
     * @return Settings_RiskFactor_Collection
     */
    public function deleteRiskFactor(Settings_RiskFactor_Factor $filter) {
        $filter->setCommand('delete');
        $this->setCollection(new Settings_RiskFactor_Collection(array($filter)));

        return $this->process();
    }

    /**
     * Update a number of RiskFactors
     *
     * @param array Array of arrays or objects representing Settings_RiskFactor_Factor data
     *
     * @return Settings_RiskFactor_Collection
     *
     * @throws Exception_InvalidRiskFactor:182591 when all elements of the $riskfactors array do not have a 'command' value set.
     */
    public function updateRiskFactors(array $riskfactors) {
        foreach ($riskfactors as &$factor) {
            if (!$factor instanceof Settings_RiskFactor_Factor) {
                $factor = new Settings_RiskFactor_Factor($factor);
            }

            if (empty($factor->getCommand())) {
                throw new Exception_InvalidRiskFactor('Instance of Settings_RiskFactor_Factor requires valid command value.', 182591);
            }
        }

        $this->setCollection(new Settings_RiskFactor_Collection($riskfactors));

        return $this->process();
    }

}
