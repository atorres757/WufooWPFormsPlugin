<?php 

require_once "WufooApi.php";
require_once "WufooFormsPluginConfigManager.php";

class WufooFormsPluginViewHelper {
	
	public $api;
	public $config;
	private $all_forms = null;
	
	public function __construct() {
		$configMgr = new WufooFormsPluginConfigManager();
		$this->config = $configMgr->load();
		$this->api = new WufooApi($this->config->wufoo_api_key, $this->config->wufoo_subdomain);
	}
	
	public function getAllForms () {
		if (is_null($this->all_forms)) {
			$this->all_forms = $this->api->getForms();
		}
		return $this->all_forms;
	}
	
	public function getForm ($hash) {
		return $this->api->getForm($hash);
	}
	
	public function getActivePublicForms () {
		return $this->getActiveForms(isset($this->config->hide_forms) ? $this->config->hide_forms : array());
	}
	
	public function getActiveForms ($excludes = array()) {
		$sorted = array();
		$forms = $this->getAllForms();
		foreach ($forms as $key => $form) {
			if (strtotime($form->EndDate) < time() || in_array($form->Hash, $excludes))
				unset($forms[$key]);
		}
		return $forms;
	}
	
	public function getFormsSelectInputField ($selectId, $value) {
		$forms = $this->getAllForms();
		$input = "<select id=\"{$selectId}\" name=\"{$selectId}\">\n";
		foreach ($forms as $form) {
			$selected = ($value == $form->Hash) ? "selected" : "";
			$input .= "<option {$selected} value=\"{$form->Hash}\">{$form->Name}</option>\n";
		}
		return $input . "</select>\n";
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
}
?>