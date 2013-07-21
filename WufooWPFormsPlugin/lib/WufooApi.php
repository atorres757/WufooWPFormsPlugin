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
		$curl = new WufooCurl();
		$response = $curl->getAuthenticated($this->baseUrl . '/forms.json', $this->apiKey);
		$response = json_decode($response);
		return ($response) ? $response->Forms : array();
	}
	
	public function getFormsDropdown() {
		try {
			$response = $this->getForms();
			echo $this->getFormsDropdownHtml($response->Forms);
		} catch (Exception $e) {
			print_r($e);
		}
	}
	
	private function getFormsDropdownHtml($forms) {
		$str = '<select>';
		if (count($forms)) {
			foreach ($forms as $form) {
				$str.= '<option value="'.$form->Hash.'">'.$form->Name.'</option>';
			}
		}
		return $str.'</select>';
	}
	
	/* -------------------------------
			  EMBED
	------------------------------- */
	
	public function embedSnippet() {
		try {
			$curl = new WufooCurl();
			$response = $curl->getAuthenticated($this->baseUrl . '/forms/web-hook-example.json', $this->apiKey);
			$response = json_decode($response);
			$hash = $response->Forms[0]->Hash;
			echo $this->getFormSnippet($hash, $this->subdomain);
		} catch (Exception $e) {
			print_r($e);
		}
	}
	
	private function getFormSnippet($hash, $subdomain) {
		return '<script type="text/javascript">var host = (("https:" == document.location.protocol) ? "https://secure." : "http://");document.write(unescape("%3Cscript src=\'" + host + "wufoo.com/scripts/embed/form.js\' type=\'text/javascript\'%3E%3C/script%3E"));</script>

		<script type="text/javascript">
		var '.$hash.' = new WufooForm();
		'.$hash.'.initialize({
		\'userName\':\''.$subdomain.'\', 
		\'formHash\':\''.$hash.'\', 
		\'autoResize\':true,
		\'height\':\'416\', 
		\'ssl\':true});
		'.$hash.'.display();
		</script>';
	}
	
	/* -------------------------------
			  SUBMIT
	------------------------------- 
	
	public function submitForm() {
		$params = array();
		$delete = array();
		
		foreach ($_POST as $key => $value) {
			$params[$key] = $value;
		}
	
		$dir = '/Users/tssabat/Desktop/upload/'.mt_rand(0, 100000).'/';
		mkdir($dir);
		
		foreach ($_FILES as $key => $value) {
			$path = $dir.str_replace('/','', str_replace('..', '', $_FILES[$key]['name']));
			move_uploaded_file($_FILES[$key]['tmp_name'], $path);
			$params[$key] = '@'.$path;
			$delete[] = $path;
		}
		
		try {
			$curl = new WufooCurl();
			$response = $curl->post(
				$params,
				$this->baseUrl . '/forms/api-submit-example/entries.json', 
				$this->apiKey);
		} catch (Exception $e) {
			print_r($e);
		}
		
		
		foreach ($delete as $file) {
			unlink($file);
		}
		rmdir($dir);
	}
	*/

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