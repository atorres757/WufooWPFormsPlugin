<?php 

require_once "WufooApi.php";
require_once "WufooFormsPluginConfigManager.php";

class WufooFormsPluginViewHelper {
	
	private $api;
	private $config;
	
	public function __construct() {
		$configMgr = new WufooFormsPluginConfigManager();
		$this->config = $configMgr->load();
		$this->api = new WufooApi($this->config->wufoo_api_key, $this->config->wufoo_subdomain);
	}
	
	public function getAllForms () {
		return $this->api->getForms();
	}
	
	public function getActiveForms () {
		
	}
}
?>