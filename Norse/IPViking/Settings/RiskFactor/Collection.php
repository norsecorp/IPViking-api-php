<?php

namespace Norse\IPViking;

/**
 * An object representation of a collection of IPViking Settings RiskFactor Request/Response data.
 */
class Settings_RiskFactor_Collection extends Response {
    protected $_riskfactors;

    /**
     * The constructor accepts either a cURL response and cURL Info array or another data
     * structure representing RiskFactor Factor data.
     *
     * @param string $curl_response The JSON response from a curl request.
     * @param array $curl_info The array result of curl_getinfo()
     *
     * @param mixed $curl_response An array of RiskFactor data or an object of RiskFactor data.
     * @param null $curl_info
     *
     * @throws Exception_InvalidRiskFactor:182592 when the data type of $curl_response is unknown.
     */
    public function __construct($curl_response, $curl_info = null) {
        if (null !== $curl_info) parent::__construct($curl_response, $curl_info);

        if (!empty($curl_response) && $curl_response !== 'null') {
            if (is_string($curl_response)) {
                $this->_processCurlResponse($curl_response);
            } elseif (is_object($curl_response)) {
                $this->_processObject($curl_response);
            } elseif (is_array($curl_response)) {
                $this->_processArray($curl_response);
            } else {
                throw new Exception_InvalidRiskFactor('Unknown format for first argument of Settings_RiskFactor_Collection constructor.', 182592);
            }
        }
    }

    protected function _processCurlResponse($curl_response) {
        if (!$decoded = json_decode($curl_response)) {
            if (version_compare(PHP_VERSION, '5.5.0', '<=') && function_exists('json_last_error_msg')) {
                $json_error_msg = json_last_error_msg();
            } else {
                $json_error_msg = 'unavailable';
            }

            throw new Exception_Json('Error decoding json response: ' . var_export(array('response' => $curl_response, 'json_error_msg' => $json_error_msg), true), json_last_error());
        }

        if (!isset($decoded->settings)) {
            throw new Exception_UnexpectedResponse('Expecting element \'settings\' in response: ' . var_export($decoded, true), 182543);
        }

        if (is_array($decoded->settings)) {
            $this->_processArray($decoded->settings);
        } elseif (is_object($decoded->settings)) {
            $this->_processObject($decoded->settings);
        } else {
            throw new Exception_UnexpectedResponse('Unexpected data type for \'settings\' in response: ' . var_export($decoded->settings, true), 182544);
        }
    }

    protected function _processObject($factor) {
        $this->addRiskFactor(new Settings_RiskFactor_Factor($factor));
    }

    protected function _processArray(array $riskfactors) {
        if (empty($riskfactors)) return null;

        foreach ($riskfactors as $factor) {
            $this->addRiskFactor(new Settings_RiskFactor_Factor($factor));
        }
    }

    /**
     * Add a Settings_RiskFactor_Factor object to the collection.
     */
    public function addRiskFactor(Settings_RiskFactor_Factor $factor) {
        $this->_riskfactors[] = $factor;
    }

    public function getRiskFactors() {
        return $this->_riskfactors;
    }

    /**
     * Returns the data of the collection as XML.
     *
     * @return null|string An XML representation of the RiskFactor data.
     */
    public function getRiskFactorXML() {
        $riskfactors = $this->getRiskFactors();

        if (empty($riskfactors) || !is_array($riskfactors)) {
            return null;
        }

        $riskfactor_xml = <<<XML
<?xml version=1.0?>
<ipviking>
    <settings>

XML;

        foreach ($riskfactors as $factor) {
            $riskfactor_xml .= <<<XML
        <riskfactors>
            <risk_id>{$factor->getRiskID()}</risk_id>
            <command>{$factor->getCommand()}</command>
            <risk_good_value>{$factor->getRiskGoodValue()}</risk_good_value>
            <risk_bad_value>{$factor->getRiskBadValue()}</risk_bad_value>
        </riskfactors>

XML;
        }

        $riskfactor_xml .= <<<XML
    </settings>
</ipviking>
XML;
        return $riskfactor_xml;
    }

}
