<?php 

require_once "WufooFormsPluginViewHelper.php";

class WufooFormsPluginShortCode {
	
	public static function render ($atts) {
		print_r($atts);
		
		$helper = new WufooFormsPluginViewHelper();
		$query = isset($_GET) ? $_GET : array();
		
		$display = isset($atts['display']) ? $atts['display'] : 'auto';
		$hash = isset($query['wuform-id']) ? $query['id'] : null;
		
		if (!empty($hash) && $display != "list") {
			$form = $helper->getForm($hash);
			echo var_dump($form);
		}else{
			$forms = $helper->getActivePublicForms();
			$ul = "<ul>\n";
			foreach ($forms as $form) {
				$ul .= "<li>{$form->Name} {$form->EndDate}</li>\n";
			}
			$ul .= "</ul>\n";
			echo $ul;
		}
	}
	
}

?>