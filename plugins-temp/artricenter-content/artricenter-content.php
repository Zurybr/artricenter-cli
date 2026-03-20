<?php
/**
 * Plugin Name: Artricenter Content Engine
 * Version: 1.0.0
 * Description: Custom Post Types for Artricenter medical clinic
 * Text Domain: artricenter-content
 * Author: Artricenter
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

namespace Artricenter\Content;

// PSR-4 autoloader
spl_autoload_register(function ($class) {
    $prefix = 'Artricenter\\Content\\';
    $base_dir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

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
    // Classes will be loaded when needed via autoloader
});

// Register CPTs on init
add_action('init', function() {
    $especialidades = new \Artricenter\Content\Especialidades();
    $especialidades->register();
});

// Add meta boxes
add_action('add_meta_boxes', function() {
    $especialidades = new \Artricenter\Content\Especialidades();
    $especialidades->add_meta_boxes();
});

// Save meta boxes
add_action('save_post', function($post_id) {
    if (get_post_type($post_id) === 'especialidad') {
        $especialidades = new \Artricenter\Content\Especialidades();
        $especialidades->save_meta_box($post_id, $_POST);
    }
});
