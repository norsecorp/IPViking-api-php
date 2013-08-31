<?php

namespace Norse\IPViking;

/**
 * Interface definition based on cURL package.
 */
interface CurlInterface {
    public function init($url = null);
    public function setOpt($option, $value);
    public function setOptArray(array $options);
    public function exec();
    public function getInfo();
    public function getLastError();
    public function getLastErrorNo();
    public function close();
}
