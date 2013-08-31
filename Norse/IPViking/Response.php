<?php

namespace Norse\IPViking;

class Response {
    /* Raw cURL response */
    protected $_curl_response;

    /* Raw cURL Info array */
    protected $_curl_info;

    protected $_url;
    protected $_content_type;
    protected $_http_code;
    // protected $_header_size;
    // protected $_request_size;
    // protected $_filetime;
    // protected $_ssl_verify_result;
    // protected $_redirect_count;
    // protected $_total_time;
    // protected $_namelookup_time;
    // protected $_connect_time;
    // protected $_pretransfer_time;
    // protected $_size_upload;
    // protected $_speed_upload;
    // protected $_upload_content_length;
    // protected $_size_download;
    // protected $_speed_download;
    // protected $_download_content_length;
    // protected $_starttransfer_time;
    // protected $_redirect_time;
    // protected $_redirect_url;
    // protected $_primary_ip;
    // protected $_certinfo;
    // protected $_primary_port;
    // protected $_local_ip;
    // protected $_local_port;

    public function __construct($curl_response, $curl_info) {
        $this->_setCurlResponse($curl_response);
        $this->_setCurlInfo($curl_info);

        if (isset($curl_info['url']))          $this->_setUrl($curl_info['url']);
        if (isset($curl_info['content_type'])) $this->_setContentType($curl_info['content_type']);
        if (isset($curl_info['http_code']))    $this->_setHttpCode($curl_info['http_code']);

        $this->_verifyResponse();
    }


    /**
     * Basic accessor methods.
     */

    protected function _setCurlResponse($curl_response) {
        $this->_curl_response = $curl_response;
    }

    public function getCurlResponse() {
        return $this->_curl_response;
    }

    protected function _setCurlInfo($curl_info) {
        $this->_curl_info = $curl_info;
    }

    public function getCurlInfo() {
        return $this->_curl_info;
    }

    protected function _setUrl($url) {
        $this->_url = $url;
    }

    public function getUrl() {
        return $this->_url;
    }

    protected function _setContentType($content_type) {
        $this->_content_type = $content_type;
    }

    public function getContentType() {
        return $this->_content_type;
    }

    protected function _setHttpCode($http_code) {
        $this->_http_code = $http_code;
    }

    public function getHttpCode() {
        return $this->_http_code;
    }


    /**
     * @throws Exception_API: when the response HTTP Code indicates an error.
     */
    protected function _verifyResponse() {
        switch ($this->getHttpCode()) {
            case '200':
            case '201':
            case '202':
            case '204':
            case '302':
                // Response code indicates success.
                break;
            case '400':
                throw new Exception_API("Bad Request\nNot an IPViking API Key.", 400);
                break;
            case '401':
                throw new Exception_API("Unauthorized\nInvalid IPViking API Key.", 401);
                break;
            case '402':
                throw new Exception_API("Payment Required\nSubscription has expired; payment needed.", 402);
                break;
            case '405':
                throw new Exception_API("Method Not Allowed\nNot a supported HTTP method.", 405);
                break;
            case '409':
                throw new Exception_API("Conflict\nRecord already exists.", 409);
                break;
            case '415':
                throw new Exception_API("Unsupported Media Type\nUnsupported MIME/Media type.", 415);
                break;
            case '417':
                throw new Exception_API("Expectation Failed\nInvalid supplied IP address.", 417);
                break;
            case '418':
                throw new Exception_API("Wrong Action\nInvalid action supplied.", 418);
                break;
            case '419':
                throw new Exception_API("Wrong Category\nInvalid category supplied.", 419);
                break;
            case '420':
                throw new Exception_API("GeoFilter Country Error\nInvalid or missing country filter.", 420);
                break;
            case '421':
                throw new Exception_API("GeoFilter Region Error\nInvalid or missing region filter.", 421);
                break;
            case '422':
                throw new Exception_API("GeoFilter City Error\nInvalid or missing city filter.", 422);
                break;
            case '423':
                throw new Exception_API("GeoFilter Zip Error\nInvalid or missing zip filter.", 423);
                break;
            case '424':
                throw new Exception_API("XML Command Error\nMising or invalid command supplied in XML string.", 424);
                break;
            case '426':
                throw new Exception_API("Upgrade Required\nSubscription upgrade required to use the request method.", 426);
                break;
            case '500':
                throw new Exception_API("Internal Server Error\nDatabase or Server Error.", 500);
                break;
            case '501':
                throw new Exception_API("Not Implemented\nRequest method not implemented or supported.", 501);
                break;
            case '503':
                throw new Exception_API("Service Unavailable\nService is currently down for unscheduld maintenance.", 503);
                break;
            case '509':
                throw new Exception_API("Bandwidth Limit Exceeded\nSandbox API Key limit of 200 requests reached.", 509);
                break;
            default:
                throw new Exception_API("Unknown Response Code\n" . var_export(array(
                    'http_code' => $this->getHttpCode(),
                    'response'  => $this->getCurlResponse(),
                ), true), 182550);
        }
    }

}
