<?php

class WufooCurl {
	
	public function __construct() {
		//set timeout here if you like.
	}
	
	public function getAuthenticated($url, $apiKey) {
		$this->curl = curl_init($url); 
		$this->setBasicCurlOptions();
		
		curl_setopt($this->curl, CURLOPT_USERPWD, $apiKey.':footastical');

		$response = curl_exec($this->curl);
		$this->setResultCodes();
		$this->checkForCurlErrors();
		$this->checkForGetErrors($response);
		curl_close($this->curl);
		return $response;
	}
	
	public function post($postParams, $url, $apiKey = '') {
		$this->curl = curl_init($url); 
		$this->setBasicCurlOptions();
		
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data', 'Expect:'));
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postParams);		
		if ($apiKey) curl_setopt($this->curl, CURLOPT_USERPWD, $apiKey.':footastical');

		$response = curl_exec($this->curl);
		$this->setResultCodes();
		$this->checkForCurlErrors();
		$this->checkForPostErrors($response);
		curl_close($this->curl);
		return $response;
	}
	
	private function setBasicCurlOptions() {
		//http://bugs.php.net/bug.php?id=47030
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl, CURLOPT_USERAGENT, 'Wufoo API Wrapper');
		curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	}
	
	private function setResultCodes() {
		$this->ResultStatus = curl_getinfo($this->curl);		
	}
	
	private function checkForCurlErrors() {
		if(curl_errno($this->curl)) {
			if ($closeConnection) curl_close($this->curl);
			throw new Exception(curl_error($this->curl), curl_errno($this->curl));
		}
	}
	
	private function checkForGetErrors($response) {
		switch ($this->ResultStatus['http_code']) {
			case 200:
				//ignore, this is good.
				break;
			case 401:
				throw new Exception('(401) Forbidden.  Check your API key.', 401);
				break;
			default:
				$this->throwResponseError($response);
				break;
		}
	}
	
	private function checkForPostErrors($response) {
		switch ($this->ResultStatus['http_code']) {
			case 200:
			case 201:
				//ignore, this is good.
				break;
			case 401:
				throw new Exception('(401) Forbidden. Check your API key.', 401);
				break;
			default:
				$this->throwResponseError($response);
				break;
		}
	}
	
	private function throwResponseError($response) {
		if ($response) {
			$obj = json_decode($response);
			throw new Exception('('.$obj->HTTPCode.') '.$obj->Text, $this->ResultStatus['HTTP_CODE']);
		} else {
			throw new Exception('('.$this->ResultStatus['HTTP_CODE'].') This is embarrassing... We did not anticipate this error type.  Please contact support here: support@wufoo.com', $this->ResultStatus['HTTP_CODE']);
		}
		return $response;
	}
	
}
?>