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

// PSR-4 autoloader with underscore-to-hyphen conversion
spl_autoload_register(function ($class) {
    $prefix = 'Artricenter\\Content\\';
    $base_dir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    // Convert namespace separators to slashes, then underscores to hyphens, prefix with 'class-'
    $file = $base_dir . 'class-' . str_replace('_', '-', strtolower(str_replace('\\', '/', $relative_class))) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Activation hook
register_activation_hook(__FILE__, function() {
    // Create pages on activation
    $page_creator = new \Artricenter\Content\Page_Creator();
    $page_creator->create_pages();

    // Flush rewrite rules on activation
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    // Flush rewrite rules on deactivation
    flush_rewrite_rules();
});

// Register CPTs on init
add_action('init', function() {
    $doctores = new \Artricenter\Content\Doctores();
    $doctores->register();

    $especialidades = new \Artricenter\Content\Especialidades();
    $especialidades->register();

    $sucursales = new \Artricenter\Content\Sucursales();
    $sucursales->register();
});

// Add meta boxes
add_action('add_meta_boxes', function() {
    $doctores = new \Artricenter\Content\Doctores();
    $doctores->add_meta_boxes();

    $especialidades = new \Artricenter\Content\Especialidades();
    $especialidades->add_meta_boxes();

    $sucursales = new \Artricenter\Content\Sucursales();
    $sucursales->add_meta_boxes();
});

// Save meta boxes
add_action('save_post', function($post_id, $post) {
    // Skip autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verify nonce
    if (!isset($_POST['artricenter_meta_box_nonce']) || !wp_verify_nonce($_POST['artricenter_meta_box_nonce'], 'artricenter_save_meta')) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $post_type = get_post_type($post_id);

    if ($post_type === 'doctor') {
        $doctores = new \Artricenter\Content\Doctores();
        $doctores->save_meta_box($post_id, $post);
    }

    if ($post_type === 'especialidad') {
        $especialidades = new \Artricenter\Content\Especialidades();
        $especialidades->save_meta_box($post_id, $post);
    }

    if ($post_type === 'sucursal') {
        $sucursales = new \Artricenter\Content\Sucursales();
        $sucursales->save_meta_box($post_id, $post);
    }
}, 10, 2);

// Register shortcodes
add_action('init', function() {
    $shortcodes = new \Artricenter\Content\Shortcodes();
    $shortcodes->register();
});
