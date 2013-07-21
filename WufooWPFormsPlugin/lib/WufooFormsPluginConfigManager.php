<?php 

require_once "WufooFormsPluginConfig.php";

class WufooFormsPluginConfigManager {
	
	private $filename = "config.json";
	private $path = ".";
	
	public function __construct () {
		$this->path = realpath(dirname(__FILE__) . "/../");
	}
	
	public function load () {
		$contents = "";
		if (is_file($this->path . "/" . $this->filename))
			$contents = file_get_contents($this->path . "/" . $this->filename);
		return json_decode($contents);
	}
	
	public function save (array $data) {
		$saved = false;
		$bytes = 0;
		$f = fopen($this->path . "/" . $this->filename, 'w+');
		if ($f) {
			$bytes = fwrite($f, json_encode($data));
			fclose($f);
		}else{
			throw new Exception ("Couldn't open {$this->path}/{$this->filename} to save configuration.");
		}
		return (!empty($data) && $bytes > 0) ? true : false;
	}
	
}
?>