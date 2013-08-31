<?php

include '../vendor/autoload.php';


// $config = array();
// $config = array('proxy' => 'universal', 'api_key' => '699e54ef34b17df8c9fdf43daf3177297a90adbb538d5be5d9454470e5e4fc92');
$config = array('proxy' => 'http://labs.ipviking.com/api/', 'api_key' => '699e54ef34b17df8c9fdf43daf3177297a90adbb538d5be5d9454470e5e4fc92');
// $config = array('proxy' => 'http://jmc.mac/index.php', 'api_key' => '1234');
// $config = './test_config.ini';
$ipv = new Norse\IPViking($config);

//G $riskFactors = $ipv->getRiskFactorSettings();
//G var_dump($riskFactors);

//G $geoFilters = $ipv->getGeoFilterSettings();
//G var_dump($geoFilters->getHttpCode());

//G $sub = $ipv->submission('67.13.46.123', 7, 51, time());
//G var_dump($sub);

//G $ipq = $ipv->ipq('67.13.46.123');
//G var_dump($ipq);

exit();



$ipqr = $ipv->getIPQRequest('62.45.32.31');

$ipqr->setTransID('1234');
$ipqr->setClientID('3456');
$ipqr->setCustomID('asdf');

$ipqr->setCategories(array(1, 2, 3, 4));
$ipqr->setOptions(array('noresolve', 'supress', 'compress'));

$ipqr->setAddress('1049 Market Street');
$ipqr->setCity('San Francisco');
$ipqr->setZip(94103);
$ipqr->setState('California');
$ipqr->setCountry('US');

$ipq = $ipqr->process();
var_dump($ipq->getRiskColor());
