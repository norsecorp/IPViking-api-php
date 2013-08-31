<?php
/*~ IPVikingAPI.php
.---------------------------------------------------------------------------.
|  Software: IPviking API - PHP class                                       |
|   Version: 0.2                                                            |
|   Contact: ts@ipviking.com										        |
|      Info: https://developer.ipviking.com                                        |
|   Support: http://support.ipviking.com/                                |
| ------------------------------------------------------------------------- |
|   Authors: Tommy Stiansen ts@norse-corp.com                               |
| Copyright (c) 2010, Norse Corporation. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Creative Commons file that came with the software              |
'---------------------------------------------------------------------------'
*/

/**
 * IPviking API PHP - provides API to ipviking.com
 * NOTE: Requires PHP version 5 or later
 * @package IPviking.phpapi
 * @author Tommy Stiansen
 * @copyright 2010 Norse Corporation
 */

if (version_compare(PHP_VERSION, '5.0.0', '<') ) exit("Sorry, this version of IPviking API will only run on PHP version 5 or greater!\n");

class IPvikingRequest
{
	protected $url;
	protected $verb;
	protected $requestBody;
	protected $requestLength;
	protected $acceptType;
	protected $responseBody;
	protected $responseInfo;
	protected $apikey;
	protected $method;	
	protected $config_risk_xsl = 'http://developer.ipviking.com/data/risk.xsl';
	protected $config_ipq_xsl = 'http://developer.ipviking.com/data/ipq.xsl';
	public $display_reason = '';
	public $display_check = '';
	protected $details_url_base = 'http://us.api.ipviking.com/details.php?r=';
	public $urldisplay = 0;
	public $score=100;
	public $ipq=0;
	
	public function __construct ($url = null, $verb = 'POST', $requestBody = TRUE) {
		$this->url				= $url;
		$this->verb				= $verb;
		$this->requestBody		= $requestBody;
		$this->requestLength	= 0;
		$this->username			= null;
		$this->password			= null;
		$this->acceptType		= 'application/json';
		$this->responseBody		= null;
		$this->responseInfo		= null;
		
		if ($this->requestBody !== null) {
			$this->buildPostBody();
		}
	}	
	public function flush () {
		$this->requestBody		= null;
		$this->requestLength	= 0;
		$this->verb				= 'POST';
		$this->responseBody		= null;
		$this->responseInfo		= null;
	}
	
	public function execute () {
		$ch = curl_init();
		try {
			switch (strtoupper($this->verb)) {
				case 'POST':
					$this->executePost($ch);
					break;
				case 'PUT':
					$this->executePut($ch);
					break;
				default:
					throw new InvalidArgumentException('Current verb (' . $this->verb . ') is an invalid REST verb.');
			}
		}
		catch (InvalidArgumentException $e) {
			curl_close($ch);
			throw $e;
		}
		catch (Exception $e) {
			curl_close($ch);
			throw $e;
		}		
	}
	public function ipvikingDisplayStatus($t,$ent,$method) {
		switch($method) {
			case 'submission':
				$http_code = $this->getStatusCodeMessage($t);
				$this->display_check .= "<b>IPviking Response:</b> $method of $ent response <b>($t) $http_code</b> <br>\n";				
				break;
			case 'risk':
				switch($t) {
					case 302:
						if($this->Geturldisplay()) 
						{
							switch($this->getAcceptType()) {
								case 'application/json':
									$output = json_decode($this->getResponseBody(),true);
									$score = $output['response']['risk_factor'];
									break;
								case 'application/xml':
									$output = simplexml_load_string($this->getResponseBody());
									$score = $output->response->risk_factor;
									break;
							}
							if($score!=0) 
								$uri = "<a href=".$this->GenerateDetailsUrl($ent)." target=_blank>details</a>";
								$this->display_check .= "<b>IPviking Response:</b> $ent Risk ".$score."% $uri<br>\n";
							} else 
							$this->display_check .= "<b>IPviking Response:</b> $ent Risk ".$score."% <br>\n";
						// set global score
						$this->SetRisk($score);
						break;
				}
				break;
			case 'ipq':
				switch($t) {
					case 302:
						if($this->Geturldisplay()) 
						{
							switch($this->getAcceptType()) {
								case 'application/json':
									$output = json_decode($this->getResponseBody(),true);
									$score = $output['response']['risk_factor'];
									break;
								case 'application/xml':
									$output = simplexml_load_string($this->getResponseBody());
									$score = $output->response->risk_factor;
									break;
							}
							if($score!=0) 
								$uri = "<a href=".$this->GenerateDetailsUrl($ent)." target=_blank>details</a>";
								$this->display_check .= "<b>IPviking Response:</b> $ent Risk ".$score."% $uri<br>\n";
							} else 
							$this->display_check .= "<b>IPviking Response:</b> $ent Risk ".$score."% <br>\n";
						// set global score
						$this->SetRisk($score);
						break;
				}
				break;
		}
	}
	public function SetRisk($score) {
		$this->risk = $score;
	}
	public function GetRisk() {
		return $this->risk;
	}
	public function SetScore($score) {
		$this->score = $score;
	}
	public function GetScore() {
		return $this->score;
	}
	public function IPvikingShowCheck() {
		return $this->display_check;
	}
	public function ipvikingDisplayReasons($style,$method) {
			switch($this->getAcceptType()) {
				case 'application/xml':				
					$xp = new XsltProcessor();
					$xp->registerPHPFunctions();	
					$xsl = new DomDocument;
					switch($method) {
						case 'risk':
  							$xsl->load($this->config_risk_xsl);
							break;
						case 'ipq':
  							$xsl->load($this->config_ipq_xsl);
							break;
					}
					$xp->importStylesheet($xsl);
					$xml_doc = new DomDocument;
					$xml_doc->loadXML($this->getResponseBody());	
					if ($html = $xp->transformToXML($xml_doc)) 
					{
      					$return = $html;
  					}
					$this->display_reason .= $return;
					break;
				case 'application/json':
					switch($method) {
						case 'risk':
							$output = json_decode($this->getResponseBody(),true);
							if($output['response']['entries']>=1) 
							{
								$ret = "<table border=\"1\" class=ipvikingdetails><tr>
                					<th>Risk Type</th>
                					<th>Risk Factor</th>
            						</tr>";
								foreach($output['response']['details'][0] as $i => $val) 
								{
									$ret .= "<tr>";
									$name = ucwords(str_replace("_"," ",$i));
									$factor = $val;
									$ret .= "<td>$name</td>";
									$ret .= "<td align=right>$factor</td>";
									$ret .= "</tr>";
								}
								$ret .= "</table>";
								$this->display_reason .= $ret;							
							}							
							break;
						case 'ipq':
							$output = json_decode($this->getResponseBody(),true);
							if($output['response']['entries']>=1) 
							{
								$ret = "<table border=\"1\" class=ipvikingdetails><tr>
                					<th>Risk Type</th>
                					<th>Risk Factor</th>
            						</tr>";
								foreach($output['response']['details'][0] as $i => $val) 
								{
									$ret .= "<tr>";
									$name = ucwords(str_replace("_"," ",$i));
									$factor = $val;
									$ret .= "<td>$name</td>";
									$ret .= "<td align=right>$factor</td>";
									$ret .= "</tr>";
								}
								$ret .= "</table>";
								$this->display_reason .= $ret;							
							}							
							break;
					}
					break;
				default:
				
			}
	}
	public function IPvikingShowReasons() {
		return $this->display_reason;
	}
	
	public function buildPostBody ($data = null)
	{
		$data = ($data !== null) ? $data : $this->requestBody;		
		if (!is_array($data)) {
			throw new InvalidArgumentException('Invalid data input for postBody.  Array expected');
		}
		$data = http_build_query($data, '', '&');
		$this->requestBody = $data;
	}	
	protected function executePost ($ch) {
		if (!is_string($this->requestBody)) {
			$this->buildPostBody();
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestBody);
		curl_setopt($ch, CURLOPT_POST, 1);	
		$this->doExecute($ch);	
	}	
	protected function executePut ($ch) {
		if (!is_string($this->requestBody)) {
			$this->buildPostBody();
		}		
		$this->requestLength = strlen($this->requestBody);
		$fh = fopen('php://memory', 'rw');
		fwrite($fh, $this->requestBody);
		rewind($fh);
		curl_setopt($ch, CURLOPT_INFILE, $fh);
		curl_setopt($ch, CURLOPT_INFILESIZE, $this->requestLength);
		curl_setopt($ch, CURLOPT_PUT, true);
		$this->doExecute($ch);		
		fclose($fh);
	}	
	protected function doExecute (&$curlHandle) {
		$this->setCurlOpts($curlHandle);
		$this->responseBody = curl_exec($curlHandle);
		$this->responseInfo	= curl_getinfo($curlHandle);
	}	
	protected function setCurlOpts (&$curlHandle) {
		curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
		curl_setopt($curlHandle, CURLOPT_URL, $this->url);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array ('Accept: ' . $this->acceptType));
	}	
	static function convertDate( $i ) {
    	return date('Y-m-d H:i T', $i );
	}
	public function getAcceptType () {
		return $this->acceptType;
	} 	
	public function setAcceptType ($acceptType) {
		$this->acceptType = $acceptType;
	} 	
	public function getResponseBody () {
		return $this->responseBody;
	} 	
	public function getResponseInfo () {
		return $this->responseInfo;
	} 	
	public function getUrl () {
		return $this->url;
	} 
	public function setapikey ($apikey) {
		$this->apikey = array('apikey' => $apikey);
	}
	public function getMethod () {
		return $this->method;
	}
	public function setMethod ($method) {
		$this->method = $method;
	}
	public function setUrl ($url) {
		$this->url = $url;
	}
	public function getVerb () {
		return $this->verb;
	} 	
	public function setVerb ($verb) {
		$this->verb = $verb;
	} 
	public static function getStatusCodeMessage($status) {   
        $codes = Array(   
            100 => 'Continue',   
            101 => 'Switching Protocols',   
            200 => 'OK',   
            201 => 'Created',   
            202 => 'Accepted',   
            203 => 'Non-Authoritative Information',   
            204 => 'No Content',   
            205 => 'Reset Content',   
            206 => 'Partial Content',   
            300 => 'Multiple Choices',   
            301 => 'Moved Permanently',   
            302 => 'Found',   
            303 => 'See Other',   
            304 => 'Not Modified',   
            305 => 'Use Proxy',   
            306 => '(Unused)',   
            307 => 'Temporary Redirect',   
            400 => 'Bad Request',   
            401 => 'Unauthorized',   
            402 => 'Payment Required',   
            403 => 'Forbidden',   
            404 => 'Not Found',   
            405 => 'Method Not Allowed',   
            406 => 'Not Acceptable',   
            407 => 'Proxy Authentication Required',   
            408 => 'Request Timeout',   
            409 => 'Conflict',   
            410 => 'Gone',   
            411 => 'Length Required',   
            412 => 'Precondition Failed',   
            413 => 'Request Entity Too Large',   
            414 => 'Request-URI Too Long',   
            415 => 'Unsupported Media Type',   
            416 => 'Requested Range Not Satisfiable',   
            417 => 'Expectation Failed',   
            500 => 'Internal Server Error',   
            501 => 'Not Implemented',   
            502 => 'Bad Gateway',   
            503 => 'Service Unavailable',   
            504 => 'Gateway Timeout',   
            505 => 'HTTP Version Not Supported'  
        );  
        return (isset($codes[$status])) ? $codes[$status] : '';   
    }   
}

?>