<?php
/**
 * Plugin Name: Artricenter Content Engine
 * Version: 1.0.0
 * Description: Custom Post Types for Artricenter medical clinic
 * Text Domain: artricenter-content
 * Author: Artricenter
 * Requires at least: 6.0
 * Requires PHP: 8.0
 *
 * @package Artricenter\Content
 */

namespace Artricenter\Content;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// PSR-4 autoloader
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'Artricenter\\Content\\';

    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/includes/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Activation hook
register_activation_hook(__FILE__, function() {
    // Flush rewrite rules on activation
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    // Flush rewrite rules on deactivation
    flush_rewrite_rules();
});

// Initialize plugin on plugins_loaded
add_action('plugins_loaded', function() {
    // Load CPT classes
    if (class_exists('Artricenter\Content\Doctores')) {
        $doctores = new Doctores();
        $doctores->init();
    }
});
