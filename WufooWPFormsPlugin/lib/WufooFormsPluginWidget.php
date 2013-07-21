<?php 

require_once "WufooFormsPluginConfigManager.php";
require_once "WufooFormsPluginViewHelper.php";

class WufooFormsPluginWidget extends WP_Widget {

	public function __construct() {
		// widget actual processes
		parent::__construct(
			'wufoo_forms_plugin_widget', // Base ID
			'Wufoo Forms Plugin Widget', // Name
			array( 'description' => __( 'Wufoo Forms Plugin', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
		$viewHelper = new WufooFormsPluginViewHelper();
		$forms = $viewHelper->getActivePublicForms();
		$ul = "<ul>\n";
		foreach ($forms as $form) {
			$ul .= "<li>{$form->Name} {$form->EndDate}</li>\n";
		}
		$ul .= "</ul>\n";
		echo $ul;
	}

 	public function form( $instance ) {
		// outputs the options form on admin
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}
?>