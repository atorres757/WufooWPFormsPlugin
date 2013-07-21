<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   WufooWPFormsPlugin
 * @author    Allen Torres <atorres757@gmail.com>
 * @license   MIT
 * @link      http://atzds.com
 * @copyright 2013 ATZ Dizyne
 *
 * @wordpress-plugin
 * Plugin Name: WufooWPFormsPlugin
 * Plugin URI:  https://github.com/atorres757/WufooWPFormsPlugin
 * Description: A Wordpress plugin that will allow you to pick which forms are shown on a sidebar widget and a preview page.
 * Version:     1.0.0
 * Author:      Allen Torres
 * Author URI:  http://atzds.com
 * Text Domain: wufoowpformsplugin-en
 * License:     MIT
 * License URI: http://opensource.org/licenses/MIT
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO: replace `class-plugin-name.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'WufooWPFormsPlugin.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'WufooWPFormsPlugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WufooWPFormsPlugin', 'deactivate' ) );

WufooWPFormsPlugin::get_instance();