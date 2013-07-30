<?php 

require_once "WufooFormsPluginViewHelper.php";

/** 
 * Hook for adding a meta box to edit post
 */
class WufooFormsPluginMetaBox {
	const LANG = 'some_textdomain';
	private $plugin_name = "wufoo_forms_plugin_meta_box";
	private $separator = "::";
	public $viewHelper;

	public function __construct() {
		$this->viewHelper = new WufooFormsPluginViewHelper();
	}
	
	public function getPluginName () {
		return $this->plugin_name;
	}
	
	public function getSeparator () {
		return $this->separator;
	}

	/**
	 * Adds the meta box container
	 */
	public static function add_meta_box() {
		add_meta_box(
			"wufoo_forms_plugin_meta_box"
			,__( 'Wufoo Form Links', WufooFormsPluginMetaBox::LANG )
			,array( new WufooFormsPluginMetaBox(), 'render_meta_box_content' )
			,'page'
			,'advanced'
			,'high'
		);
		add_meta_box(
			"wufoo_forms_plugin_meta_box"
			,__( 'Wufoo Form Links', WufooFormsPluginMetaBox::LANG )
			,array( new WufooFormsPluginMetaBox(), 'render_meta_box_content' )
			,'post'
			,'advanced'
			,'high'
		);
	}

	public function save( $post_id ) {
		// First we need to check if the current user is authorised to do this action. 
		if ( 'page' == $_REQUEST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return;
		}

		// Secondly we need to check if the user intended to change this value.
		if ( ! isset( $_POST[$this->plugin_name . '_noncename'] ) || ! wp_verify_nonce( $_POST[$this->plugin_name . '_noncename'], plugin_basename( __FILE__ ) ) )
			return;

		// Thirdly we can save the value to the database

		//if saving in a custom table, get post_ID
		$post_ID = $_POST['post_ID'];
		if (isset($_POST[$this->plugin_name . "_text"]) && isset($_POST[$this->plugin_name . "_form"])) {
			foreach ($_POST[$this->plugin_name . "_text"] as $idx => $text) {
				if (empty($text)) continue;
				$data = sanitize_text_field($text . $this->separator . "http://{$this->viewHelper->config->wufoo_subdomain}.wufoo.com/forms/".$_POST[$this->plugin_name . "_form"][$idx]);
				$meta_key = $this->plugin_name . "_{$idx}";
				
				if ( !add_post_meta( $post_ID, $meta_key, $data, true ) ) {
					//update_post_meta( $post_ID, $meta_key, $data );
				}
			}
		}
		if (isset($_POST[$this->plugin_name . "_remove"])) {
			//die(print_r($_POST[$this->plugin_name . "_remove"], true));
			foreach ($_POST[$this->plugin_name . "_remove"] as $key => $val) {
				delete_post_meta( $post_ID, $this->plugin_name . "_{$val}");
			}
		}
	}


	/**
	 * Render Meta Box content
	 */
	public function render_meta_box_content( $post ) {
		
		$forms = $this->viewHelper->getAllForms();
		if (count($forms) === 0) {
			echo "There are no Wufoo Forms Available.";
			return;
		}
		
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), $this->plugin_name . '_noncename' );

		// The actual fields for data entry
		// Use get_post_meta to retrieve an existing value from the database and use the value for the form
		$values = get_post_meta( $post->ID );
		foreach ($values as $key => $data) {
			if (preg_match("/^{$this->plugin_name}/", $key)) {
				$this->renderFieldSet (substr($key, -1), $data[0], true);
			}
		}
		echo "<h4>New Link</h4>";
		$this->renderFieldSet ('new', "");
	}
	
	public function renderFieldSet ($id, $value, $showRemove = false) {
		list($text, $url) = explode($this->separator, $value);
		echo '<label for="'.$this->plugin_name.'_'.$id.'_text">';
		_e( 'Link Text', $this->plugin_name . '_textdomain' );
		echo '</label> ';
		echo '<input type="text" id="'.$this->plugin_name.'_'.$id.'_text" name="'.$this->plugin_name.'_text[]" value="'.esc_attr( $text ).'" size="25" max-length="255" />';
		echo '<label for="'.$this->plugin_name.'_form">';
		_e( 'Wufoo Form', $this->plugin_name . '_textdomain' );
		echo '</label> ';
		echo $this->viewHelper->getFormsSelectInputField($this->plugin_name.'_form[]', basename($url)) . "\n";
		if ($showRemove) {
			echo '<label for="'.$this->plugin_name.'_'.$id.'_remove">';
			_e( 'Remove', $this->plugin_name . '_textdomain' );
			echo '</label> ';
			echo '<input type="checkbox" id="'.$this->plugin_name.'_'.$id.'_remove" name="'.$this->plugin_name.'_remove[]" value="'.$id.'" />';
		}
		echo "<br />\n";
	}
}
?>