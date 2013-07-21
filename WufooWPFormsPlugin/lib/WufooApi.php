<?php 

require_once "WufooCurl.php";

class WufooApi {
	
	private $apiKey;
	private $subdomain;
	private $baseUrl;
	
	public function __construct ($apiKey, $subdomain) {
		$this->apiKey = $apiKey;
		$this->subdomain = $subdomain;
		$this->baseUrl = 'https://'.$this->subdomain.'.wufoo.com/api/v3';
	}
	
	/* -------------------------------
			  FORMS
	------------------------------- */
	
	public function getForms () {
		try {
			$curl = new WufooCurl();
			$response = $curl->getAuthenticated($this->baseUrl . '/forms.json', $this->apiKey);
			$response = json_decode($response);
		}catch (Exception $ex) {
			error_log($ex->getMessage());
		}
		return ($response) ? $response->Forms : array();
	}
	
	
	public function getForm ($hash) {
		try {
			$curl = new WufooCurl();
			$response = $curl->getAuthenticated($this->baseUrl . '/forms/' . $hash . '.json', $this->apiKey);
			$response = json_decode($response);
		}catch (Exception $ex) {
			error_log($ex->getMessage());
		}
		return ($response) ? $response->Forms[0] : array();
	}

	/* -------------------------------
			  LOGIN
	------------------------------- 
	
	public function login() {
		$subdomain = '';
		$curl = new WufooCurl();
		$params = array(
			'email' => 'name@domain.com',
			'password' => 'pw',
			'integrationKey' => 'getMeFromWufoo');
			
		if ($subdomain) {
			$params['subdomain'] = $subdomain;
		}
			
		try {
			$response = $curl->post(
				$params,
				'https://wufoo.com/api/v3/login.xml');
			echo htmlentities($response);
		} catch (Exception $e) {
			print_r($e);
		}
		
	}
	*/
}

?>