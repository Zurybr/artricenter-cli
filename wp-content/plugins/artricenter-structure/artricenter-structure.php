<?php
/**
 * Plugin Name: Artricenter Structure
 * Plugin URI: https://artricenter.com.mx
 * Description: Provides site-wide layout components (header, footer, navigation) and global CSS for Artricenter
 * Version: 1.0.0
 * Author: Artricenter
 * Author URI: https://artricenter.com.mx
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: artricenter-structure
 * Domain Path: /languages
 *
 * @package Artricenter_Structure
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoloader for Artricenter\Structure classes.
 *
 * @param string $class The fully-qualified class name.
 */
spl_autoload_register( function( $class ) {
	// Project-specific namespace prefix.
	$prefix = 'Artricenter\\Structure\\';

	// Base directory for the namespace prefix.
	$base_dir = __DIR__ . '/includes/';

	// Does the class use the namespace prefix?
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	// Get the relative class name.
	$relative_class = substr( $class, $len );

	// Replace namespace separators with directory separators.
	// Convert class name to filename (e.g., Header → class-header.php).
	$file = $base_dir . 'class-' . strtolower( str_replace( '_', '-', str_replace( '\\', '/', $relative_class ) ) ) . '.php';

	// If the file exists, require it.
	if ( file_exists( $file ) ) {
		require $file;
	}
} );

/**
 * Main plugin class.
 */
class Artricenter_Structure_Plugin {

	/**
	 * Initialize the plugin.
	 */
	public function run() {
		// Load template tags.
		require_once plugin_dir_path( __FILE__ ) . 'templates/template-tags.php';
	}
}

// Initialize the plugin.
add_action( 'plugins_loaded', function() {
	$plugin = new Artricenter_Structure_Plugin();
	$plugin->run();
} );
