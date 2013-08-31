<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking RiskFactor Settings data.
 */
class Settings_RiskFactor_Factor {
    protected $_risk_id;
    protected $_command;
    protected $_risk_attribute;
    protected $_risk_good_value;
    protected $_risk_bad_value;

    /**
     * The constructor accepts either an object or an array of data.
     *
     * @param mixed $filter An object or array encapsulating RiskFactor data.
     *
     * @throws Exception_InvalidRiskFactor:182580 when the provided argument is neither an object nor an array.
     */
    public function __construct($factor = null) {
        if (!empty($factor)) {
            if (is_object($factor)) {
                $this->_processObject($factor);
            } elseif (is_array($factor)) {
                $this->_processArray($factor);
            } else {
                throw new Exception_InvalidRiskFactor('Unexpected format for instantiation of Norse\IPViking\Settings_RiskFactor_Factor object.', 182590);
            }
        }
    }

    protected function _processObject($factor) {
        if (isset($factor->risk_id))         $this->setRiskID($factor->risk_id);
        if (isset($factor->command))         $this->setCommand($factor->command);
        if (isset($factor->risk_attribute))  $this->setRiskAttribute($factor->risk_attribute);
        if (isset($factor->risk_good_value)) $this->setRiskGoodValue($factor->risk_good_value);
        if (isset($factor->risk_bad_value))  $this->setRiskBadValue($factor->risk_bad_value);
    }

    protected function _processArray($factor) {
        if (isset($factor['risk_id']))         $this->setRiskID($factor['risk_id']);
        if (isset($factor['command']))         $this->setCommand($factor['command']);
        if (isset($factor['risk_attribute']))  $this->setRiskAttribute($factor['risk_attribute']);
        if (isset($factor['risk_good_value'])) $this->setRiskGoodValue($factor['risk_good_value']);
        if (isset($factor['risk_bad_value']))  $this->setRiskBadValue($factor['risk_bad_value']);
    }


    /**
     * Basic accessor methods.
     */

    public function setRiskID($risk_id) {
        $this->_risk_id = $risk_id;
    }

    public function getRiskID() {
        return $this->_risk_id;
    }

    public function setCommand($command) {
        $command = strtolower($command);
        if ($command !== 'add' && $command !== 'delete') {
            throw new Exception_InvalidRiskFactor('Invalid value for Settings_RiskFactor_Factor::command; expecting \'add\' or \'delete\', given ' .  var_export($command, true), 182599);
        }

        $this->_command = $command;
    }

    public function getCommand() {
        return $this->_command;
    }

    public function setRiskAttribute($risk_attribute) {
        $this->_risk_attribute = $risk_attribute;
    }

    public function getRiskAttribute() {
        return $this->_risk_attribute;
    }

    public function setRiskGoodValue($risk_good_value) {
        if (!is_numeric($risk_good_value)) {
            throw new Exception_InvalidRiskFactor('Settings_RiskFactor_Factor::risk_good_value must be an integer, given ' . gettype($risk_good_value), 182593);
        }

        $this->_risk_good_value = $risk_good_value;
    }

    public function getRiskGoodValue() {
        return $this->_risk_good_value;
    }

    public function setRiskBadValue($risk_bad_value) {
        if (!is_numeric($risk_bad_value)) {
            throw new Exception_InvalidRiskFactor('Settings_RiskFactor_Factor::risk_bad_value must be an integer, given ' . gettype($risk_bad_value), 182596);
        }

        $this->_risk_bad_value = $risk_bad_value;
    }

    public function getRiskBadValue() {
        return $this->_risk_bad_value;
    }

}
