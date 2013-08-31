<?php

namespace NorseTest;

class Curl implements \Norse\IPViking\CurlInterface {
    /* An array used to record settings passed to the cURL object */
    protected $_data = array();

    /**
     * A convenience method used to find and return the API Key, if set.
     *
     * @returns int The API Key if found, otherwise 0.
     */
    protected function _getAPIKey() {
        foreach ($this->_data as $data) {
            if (is_string($data) && false !== stripos($data, 'apikey')) {
                $data = parse_str($data);
                if (!empty($apikey)) {
                    return $apikey;
                }
            }
        }

        // Failed to find an API Key, return 0
        return 0;
    }


    /**
     * The following methods return contrived cURL Info arrays.
     *
     * @return array Array of cURL Info values
     */

    protected function _getIPQCurlInfo() {
        return array(
            'url'           => 'http://ipq.test.com/',
            'content_type'  => 'application/json',
            'http_code'     => 302,
        );
    }

    protected function _getResponseFailCurlInfo() {
        return array(
            'url'           => 'http://response.fail.com/',
            'content_type'  => 'application/json',
            'http_code'     => $this->_getAPIKey(),
        );
    }

    protected function _getSubmissionCurlInfo() {
        return array(
            'url'           => 'http://submission.test.com/',
            'content_type'  => 'application/json',
            'http_code'     => 201,
        );
    }

    protected function _getGeoFilterCurlInfo() {
        return array(
            'url'           => 'http://geofilter.test.com/',
            'content_type'  => 'application/json',
            'http_code'     => 302,
        );
    }

    protected function _getRiskFactorCurlInfo() {
        return array(
            'url'           => 'http://riskfactor.test.com/',
            'contnet_type'  => 'application/json',
            'http_code'     => 302,
        );
    }


    /**
     * The following methods return contrived JSON values.
     *
     * @return string A JSON string representative of production results.
     */

    protected function _getIPQResponse() {
        $headers = $this->_data[CURLOPT_HTTPHEADER];
        foreach ($headers as $header) {
            if (strstr($header, 'Accept: ')) {
                $accept = $header;
                break;
            }
        }
        if ($accept == 'Accept: application/json') {
            return '{
    "response": {
        "method": "ipq",
        "ip": "67.13.46.123",
        "host": "NXDOMAIN",
        "clientID": 0,
        "transID": 0,
        "customID": 0,
        "risk_factor": 83,
        "risk_color": "orange",
        "risk_name": "High",
        "risk_desc": "High risk Involved",
        "timestamp": "2013-08-22T18:50:31-04:00",
        "factor_entries": 13,
        "ip_info": {
            "autonomous_system_number": "n\/a",
            "autonomous_system_name": "n\/a"
        },
        "geoloc": {
            "country": "United States",
            "country_code": "US",
            "region": "-",
            "region_code": "-",
            "city": "-",
            "latitude": "38",
            "longtitude": "-97",
            "internet_service_provider": "Century Link",
            "organization": "Century Link"
        },
        "entries": [
            {
                "category_name": "Bogon Unadv",
                "category_id": "2",
                "category_factor": "25",
                "protocol_id": "31",
                "last_active": "2013-08-16T04:31:07-04:00",
                "overall_protocol": "Unadvertised IP",
                "protocol_name": "IP unadvertised"
            }
        ],
        "factoring": [
            {
                "country_risk_factor": "4.1",
                "region_risk_factor": "0",
                "ip_resolve_factor": "8",
                "asn_record_factor": "10",
                "asn_threat_factor": 5,
                "bgp_delegation_factor": "20",
                "iana_allocation_factor": "-2",
                "ipviking_personal_factor": "-1",
                "ipviking_category_factor": 19,
                "ipviking_geofilter_factor": 0,
                "ipviking_geofilter_rule": 0,
                "data_age_factor": "20",
                "geomatch_distance": 0,
                "geomatch_factor": 0,
                "search_volume_factor": "0"
            }
        ]
    }
}';
        } elseif ($accept == 'Accept: application/xml') {
            return <<<XML
<?xml version="1.0"?>
<ipviking>
     <response>
          <method>ipq</method>
          <ip>67.13.46.123</ip>
          <host>NXDOMAIN</host>
          <clientID>0</clientID>
          <transID>0</transID>
          <customID>0</customID>
          <risk_factor>37</risk_factor>
          <risk_color>yellow</risk_color>
          <risk_name>Medium</risk_name>
          <risk_desc>Medium risk</risk_desc>
          <timestamp>2013-08-28T14:25:28-04:00</timestamp>
          <factor_entries>13</factor_entries>
          <ip_info>
               <autonomous_system_number>n/a</autonomous_system_number>
               <autonomous_system_name>n/a</autonomous_system_name>
          </ip_info>
          <geoloc>
               <country>United States</country>
               <country_code>US</country_code>
               <region>-</region>
               <region_code>-</region_code>
               <city>-</city>
               <latitude>38</latitude>
               <longtitude>-97</longtitude>
               <internet_service_provider>Century Link</internet_service_provider>
               <organization>Century Link</organization>
          </geoloc>
          <entries>
               <reason>
                    <category_name>Bogon Unadv</category_name>
                    <category_id>2</category_id>
                    <category_factor>25</category_factor>
                    <protocol_id>31</protocol_id>
                    <last_active>2013-08-23T04:31:03-04:00</last_active>
                    <overall_protocol>Unadvertised IP</overall_protocol>
                    <protocol_name>IP unadvertised</protocol_name>
               </reason>
          </entries>
          <factoring>
               <reason>
                    <country_risk_factor>4.1</country_risk_factor>
                    <region_risk_factor>0</region_risk_factor>
                    <ip_resolve_factor>12</ip_resolve_factor>
                    <asn_record_factor>10</asn_record_factor>
                    <asn_threat_factor>5</asn_threat_factor>
                    <bgp_delegation_factor>20</bgp_delegation_factor>
                    <iana_allocation_factor>-2</iana_allocation_factor>
                    <ipviking_personal_factor>-1</ipviking_personal_factor>
                    <ipviking_category_factor>19</ipviking_category_factor>
                    <ipviking_geofilter_factor>-50</ipviking_geofilter_factor>
                    <ipviking_geofilter_rule>423</ipviking_geofilter_rule>
                    <data_age_factor>20</data_age_factor>
                    <geomatch_distance>0</geomatch_distance>
                    <geomatch_factor>0</geomatch_factor>
                    <search_volume_factor>0</search_volume_factor>
               </reason>
          </factoring>
     </response>
</ipviking>
XML;
        } else {
            $this->fail('Unknown value for Accept: header.');
        }
    }

    protected function _getSubmissionResponse() {
        return 'null';
    }

    protected function _getGeoFilterResponse() {
        return '{
    "geofilters": [
        {
            "filter_id": "443",
            "action": "Allow",
            "clientID": "0",
            "category": "City",
            "country": "TW",
            "region": "04",
            "city": "PONG",
            "zip": "-",
            "hits": "0"
        },
        {
            "filter_id": "423",
            "action": "Allow",
            "clientID": "0",
            "category": "Country",
            "country": "US",
            "region": "-",
            "city": "-",
            "zip": "-",
            "hits": "4140"
        },
        {
            "filter_id": "433",
            "action": "Deny",
            "clientID": "0",
            "category": "Master",
            "country": "-",
            "region": "-",
            "city": "-",
            "zip": "-",
            "hits": "0"
        },
        {
            "filter_id": "1031",
            "action": "Allow",
            "clientID": "0",
            "category": "Master",
            "country": "-",
            "region": "-",
            "city": "-",
            "zip": "-",
            "hits": "0"
        }
    ]
}';
    }

    protected function _getRiskFactorResponse() {
        return '{
    "settings": [
        {
            "risk_id": "1",
            "risk_attribute": "Country Risk Factor",
            "risk_good_value": "99",
            "risk_bad_value": "99"
        },
        {
            "risk_id": "2",
            "risk_attribute": "Region Risk Factor",
            "risk_good_value": "99",
            "risk_bad_value": "99"
        },
        {
            "risk_id": "3",
            "risk_attribute": "IP resolve Factor",
            "risk_good_value": "-2",
            "risk_bad_value": "8"
        },
        {
            "risk_id": "4",
            "risk_attribute": "ASN Risk Factor",
            "risk_good_value": "-2",
            "risk_bad_value": "10"
        },
        {
            "risk_id": "5",
            "risk_attribute": "BGP Status Risk Factor",
            "risk_good_value": "-2",
            "risk_bad_value": "20"
        },
        {
            "risk_id": "6",
            "risk_attribute": "IANA status Risk factor",
            "risk_good_value": "-2",
            "risk_bad_value": "10"
        },
        {
            "risk_id": "7",
            "risk_attribute": "ByteWolf Risk factor",
            "risk_good_value": "-1",
            "risk_bad_value": "50"
        },
        {
            "risk_id": "8",
            "risk_attribute": "Category Risk Factor",
            "risk_good_value": "99",
            "risk_bad_value": "99"
        },
        {
            "risk_id": "9",
            "risk_attribute": "Freshness Risk Factor",
            "risk_good_value": "-15",
            "risk_bad_value": "20"
        },
        {
            "risk_id": "10",
            "risk_attribute": "Search Volume",
            "risk_good_value": "0",
            "risk_bad_value": "20"
        },
        {
            "risk_id": "11",
            "risk_attribute": "GeoFilter Factor",
            "risk_good_value": "-50",
            "risk_bad_value": "99"
        }
    ]
}';
    }


    /**
     * Methods defined and required by the cURL interface
     */

    /**
     * Wrapper for init(), returns false when expected, otherwise true.
     *
     * @param null|string The candidate URL for the cURL object.
     *
     * @return bool FALSE when forced, TRUE otherwise.
     */
    public function init($url = null) {
        if ($url == 'http://init.fail.com/') return false;

        $this->_data['url'] = $url;
        return true;
    }

    /**
     * Wrapper for setOpt(), returns false when expected, otherwise true.
     *
     * @param string $option One of the defined CURLOPT constants.
     * @param mixed $value A value defined for the given CURLOPT.
     *
     * @return bool FALSE when forced, TRUE otherwise.
     */
    public function setOpt($option, $value) {
        if ($this->_data['url'] == 'http://setopt.fail.com/') return false;

        $this->_data[$option] = $value;

        return true;
    }

    /**
     * Wrapper for setOptArray(), returns false when expected, otherwise true.
     *
     * @param array $options key->value pairs corresponding to CURLOPT->value settings.
     *
     * @return bool FALSE when forced, TRUE otherwise.
     */
    public function setOptArray(array $options) {
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return false;

        foreach ($options as $option => $value) {
            if (!$this->setOpt($option, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Wrapper for exec(), returns false when expected, true by default, otherwise the selected JSON response.
     *
     * @return mixed FALSE when forced, TRUE by default.  In general, a JSON response identified by the _data['url'] value.
     */
    public function exec() {
        if ($this->_data['url'] == 'http://exec.fail.com/')       return false;
        if ($this->_data['url'] == 'http://json.fail.com/')       return 'Invalid JSON';
        if ($this->_data['url'] == 'http://ipq.test.com/')        return $this->_getIPQResponse();
        if ($this->_data['url'] == 'http://submission.test.com/') return $this->_getSubmissionResponse();
        if ($this->_data['url'] == 'http://geofilter.test.com/')  return $this->_getGeoFilterResponse();
        if ($this->_data['url'] == 'http://riskfactor.test.com/') return $this->_getRiskFactorResponse();

        return true;
    }

    /**
     * Wrapper for getinfo(), returns false when expected, true by default, otherwise the selected cURL Info array.
     *
     * @return mixed FALSE when forced, TRUE by default.  In general, an array identified by the _data['url'] value.
     */
    public function getInfo() {
        if ($this->_data['url'] == 'http://getinfo.fail.com/')    return false;
        if ($this->_data['url'] == 'http://json.fail.com/')       return $this->_getIPQCurlInfo();
        if ($this->_data['url'] == 'http://response.fail.com/')   return $this->_getResponseFailCurlInfo();
        if ($this->_data['url'] == 'http://ipq.test.com/')        return $this->_getIPQCurlInfo();
        if ($this->_data['url'] == 'http://submission.test.com/') return $this->_getSubmissionCurlInfo();
        if ($this->_data['url'] == 'http://geofilter.test.com/')  return $this->_getGeoFilterCurlInfo();
        if ($this->_data['url'] == 'http://riskfactor.test.com/') return $this->_getRiskFactorCurlInfo();

        return true;
    }

    /**
     * Wrapper for error(), returns value expected, otherwise ''.
     *
     * @return string '' by default, otherwise an expected error string.
     */
    public function getLastError() {
        if ($this->_data['url'] == 'http://exec.fail.com/')        return 'exec fail';
        if ($this->_data['url'] == 'http://getinfo.fail.com/')     return 'getinfo fail';
        if ($this->_data['url'] == 'http://setopt.fail.com/')      return 'setopt fail';
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return 'setoptarray fail';

        return '';
    }

    /**
     * Wrapper for errno(), returns value expected, otherwise 0 by default.
     *
     * @return int 0 by default, otherwise an expected error value.
     */
    public function getLastErrorNo() {
        if ($this->_data['url'] == 'http://exec.fail.com/')        return 1001;
        if ($this->_data['url'] == 'http://getinfo.fail.com/')     return 1002;
        if ($this->_data['url'] == 'http://setopt.fail.com/')      return 1003;
        if ($this->_data['url'] == 'http://setoptarray.fail.com/') return 1004;

        return 0;
    }

    /**
     * As no socket was opened, nothing needs to be closed.  This function exists for compliance reasons.
     */
    public function close() {
    }

}
