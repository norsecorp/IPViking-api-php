<?php

namespace Norse\IPViking;

class Submission_Response extends Response {

    public function __construct($curl_response, $curl_info) {
        parent::__construct($curl_response, $curl_info);

        // We expect curl_response to be empty
    }

}
