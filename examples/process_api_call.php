<?php

include("../IPVikingAPI.php");

/*
 * This could be used when someone is attempting to login
 * One can check the IP of the person logging in for risk
 * - For example disallow proxies to login
 * - Block all high risk
 * - Show custom messages to user based on findings
 * - Use the geolocation information
 * - Assing high risk to the user and limit actions
 * - more creative ways.....
 */

$ip = $_SERVER['REMOTE_ADDR'];

$requestdata = array('apikey' => '8292777557e8eb8bc169c2af29e87ac07d0f1ac4857048044402dbee06ba5cea',
					'method' => 'ipq',
					'ip' => $ip);
$IPViking = new IPvikingRequest("http://us.api.ipviking.com/api/", "POST",$requestdata);
$IPViking->execute();
$IPViking_header = $IPViking->getResponseInfo();
$IPViking_body = $IPViking->getResponseBody();
$IPViking_http_code = $IPViking_header['http_code'];
$IPViking_json = json_decode($IPViking_body);

/* 
 * These are the categories we care about for login auth
 * We can implement more categories later
 * or even protocols if we want to see spesific malware/bad stuff
 */
$botnet = FALSE; $proxy = FALSE; $bogonu = FALSE; $bogona = FALSE; $whitelist=FALSE;

/* 
 * Code 302 is always success other codes indicate failure/errors
 * Let's proccess 302 and get information on the IP we have at hand
 */
switch($IPViking_http_code)
{
	case 302:
		/* First we get the IPQ score */
		$ipq_score = $IPViking_json->response->risk_factor;
		/* If there is entries present collect them */
		if(isset($IPViking_json->response->entries)) 
		{
			foreach($IPViking_json->response->entries AS $entries)
			{
				if($entries->category_name=="Botnet") $botnet = TRUE;
				if($entries->category_name=="Proxy") $proxy = TRUE;
				if($entries->category_name=="Bogon Unadv") $bogona = TRUE;
				if($entries->category_name=="Bogon Unass") $bogonu = TRUE;
				if($entries->category_name=="Global Whitelist") $whitelist = TRUE;
			}
			/* If whitelist is detected ignore all */
			if($whitelist==FALSE) {			
				if($proxy && $botnet)
					$error =  "Login declined due to high risk IP. ";
				elseif($botnet && $ipq_score >= 80 ) 
					$error =  "Login declined due to suspected botnet. ";
				elseif($proxy) 
					$error =  "Login declined due to use of proxy. ";
				elseif($ipq_score >= 82) 
					$error =  "Login declined due to high risk. ";
				elseif($bogonu && $ipq_score >= 65) 
					$error =  "Login declined due to high risk. ";
				elseif($ipq_score >= 51) 
					$error =  "Due to high risk your actions will be limited on the site ";
				elseif($ipq_score >= 1) 
					/* Trigger captcha for lower score to be sure*/
					$force_captcha=TRUE;
				else {
					/* Good score  no risk */
				} 
			} /* whitelist bool */ 
		} /* isset entries */
		break;
	default:
		$error = 'Error:'.$IPViking->getStatusCodeMessage($IPViking_http_code).' '.$IPViking_http_code;
		break;
} /* IPViking switch */


?>