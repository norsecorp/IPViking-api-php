<?php

namespace NorseTest;

/**
 * This class returns values similar to the cURL class, but should never be used as it fails
 * to implement the CurlInterface.
 */
class CurlNoInterface {
    protected $_data = array();

    public function init($url = null) {
        $this->_data['url'] = $url;
    }

    public function setOpt($option, $value) {
        $this->_data[$option] = $value;
    }

    public function setOptArray(array $options) {
        foreach ($options as $option => $value) {
            $this->setOpt($option, $value);
        }
    }

    public function exec() {
        return true;
    }

    public function getInfo() {
        return var_export($this->_data, true);
    }

    public function getLastError() {
        return '';
    }

    public function getLastErrorNo() {
        return 0;
    }

    public function close() {
    }

}
