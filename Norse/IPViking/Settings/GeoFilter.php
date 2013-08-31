<?php

namespace Norse\IPViking;

/**
 * An object representation of IPViking Settings GeoFilter Request data.
 */
class Settings_GeoFilter extends Request {
    protected $_collection;

    public function __construct($config) {
        parent::__construct($config);
    }


    /**
     * Basic accessor methods.
     */

    public function setCollection(Settings_GeoFilter_Collection $collection) {
        $this->_collection = $collection;
    }

    public function getCollection() {
        return $this->_collection;
    }

    /**
     * Retreives XML representation of GeoFilter data.
     *
     * @return null|string XML corresponding to GeoFilter data.
     */
    protected function _getGeoFilterXML() {
        $collection = $this->getCollection();

        if (empty($collection)) return null;
        return $this->getCollection()->getGeoFilterXML();
    }


    /**
     * cURL configuration and interaction.
     */

    /**
     * @return array An array of key->value pairs to be URL encoded for requests
     */
    protected function _getBodyFields() {
        $body_fields = parent::_getBodyFields();

        $body_fields['method']       = 'geofilter';
        $body_fields['geofilterxml'] = $this->_getGeoFilterXML();

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
     * A wrapper for curl_exec() which packages the response in a Settings_GeoFilter_Collection object.
     *
     * @return Settings_GeoFilter_Collection A response collection representing the GeoFilter response.
     */
    public function process() {
        $this->_setCurlOpts();

        $curl_response = parent::_curlExec();
        $curl_info     = parent::_curlInfo();

        return new Settings_GeoFilter_Collection($curl_response, $curl_info);
    }


    /**
     * API Methods
     */

    /**
     * @return Settings_GeoFilter_Collection
     */
    public function getCurrentSettings() {
        return $this->process();
    }

    /**
     * Adds a given filter to GeoFilter settings.
     *
     * @param Settings_GeoFilter_Filter The filter to be added.
     *
     * @return Settings_GeoFilter_Collection
     */
    public function addGeoFilter(Settings_GeoFilter_Filter $filter) {
        $filter->setCommand('add');
        $this->setCollection(new Settings_GeoFilter_Collection(array($filter)));

        return $this->process();
    }

    /**
     * Deletes the given filter from GeoFilter settings.
     *
     * @param Settings_GeoFilter_Filter The filter to be deleted.
     *
     * @return Settings_GeoFilter_Collection
     */
    public function deleteGeoFilter(Settings_GeoFilter_Filter $filter) {
        $filter->setCommand('delete');
        $this->setCollection(new Settings_GeoFilter_Collection(array($filter)));

        return $this->process();
    }

    /**
     * Update a number of GeoFilters
     *
     * @param array Array of arrays or objects representing Settings_GeoFilter_Filter data
     *
     * @return Settings_GeoFilter_Collection
     *
     * @throws Exception_InvalidGeoFilter:182581 when all elements of the $geofilters array do not have a 'command' value set.
     */
    public function updateGeoFilters(array $geofilters) {
        foreach ($geofilters as &$filter) {
            if (!$filter instanceof Settings_GeoFilter_Filter) {
                $filter = new Settings_GeoFilter_Filter($filter);
            }

            if (empty($filter->getCommand())) {
                throw new Exception_InvalidGeoFilter('Instance of Settings_GeoFilter_Filter requires valid command value.', 182581);
            }
        }

        $this->setCollection(new Settings_GeoFilter_Collection($geofilters));

        return $this->process();
    }

}
