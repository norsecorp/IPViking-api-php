<?php

namespace Norse\IPViking;

/**
 * This wrapper requires the curl extension is loaded and available.
 */
if (!in_array('curl', get_loaded_extensions()) || !function_exists('curl_version')) {
    throw new Exception_Curl('cURL extension not found.  Check PHP configuration.', 182510);
}

class Curl implements CurlInterface {
    /* The current cURL handle */
    protected $_handle;

    /**
     * Wrapper for curl_init()
     *
     * @param $url null|string The target URL for the cURL connection.
     *
     * @return resource The result of curl_init() -- it should be a curl_handle.
     */
    public function init($url = null) {
        $this->_handle = curl_init($url);
        return $this->_handle;
    }

    /**
     * Wrapper for curl_setopt()
     *
     * @param $option One of the defined CURLOPT constants.
     * @param $value A value defined for the given CURLOPT.
     *
     * @return boolean The result of curl_setopt()
     */
    public function setOpt($option, $value) {
        return curl_setopt($this->_handle, $option, $value);
    }

    /**
     * Wrapper for curl_setopt_array()
     *
     * @param array $options key->value pairs corresponding to CURLOPT->value settings.
     *
     * @return boolean The result of curl_setopt_array()
     */
    public function setOptArray(array $options) {
        return curl_setopt_array($this->_handle, $options);
    }

    /**
     * Wrapper for curl_exec()
     *
     * @return mixed The result of curl_exec()
     */
    public function exec() {
        return curl_exec($this->_handle);
    }

    /**
     * Wrapper for curl_getinfo()
     *
     * @return array The result of curl_getinfo()
     */
    public function getInfo() {
        return curl_getinfo($this->_handle);
    }

    /**
     * Wrapper for curl_error()
     *
     * @return string The result of curl_error()
     */
    public function getLastError() {
        return curl_error($this->_handle);
    }

    /**
     * Wrapper for curl_errno()
     *
     * @return int The result of curl_errno()
     */
    public function getLastErrorNo() {
        return curl_errno($this->_handle);
    }

    /**
     * Wrapper for curl_close()
     *
     * @return bool The result of curl_close()
     */
    public function close() {
        return curl_close($this->_handle);
    }

}
