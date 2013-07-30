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
		
		$formCount = 0;
		$values = get_post_meta( get_the_ID() );
		$metaBoxPlugin = new WufooFormsPluginMetaBox();
	
		$ul = "<ul>\n";
		foreach ($values as $key => $data) {
			if (preg_match("/^{$metaBoxPlugin->getPluginName()}/", $key)) {
				$formCount++;
				list($text, $link) = explode($metaBoxPlugin->getSeparator(), $data[0]);
				$ul .= '<li><a target="_blank" href="'.$link.'">'.$text.'</a></li>';
			}
		}
		$ul .= "</ul>\n";
		
		if ($formCount > 0) $ul = "<nav class=\"page-sub-menu\"><li class=\"pagenav\">Register Here {$ul}</li></nav>";
		
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