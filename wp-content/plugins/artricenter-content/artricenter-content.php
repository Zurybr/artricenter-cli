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

// Require class files directly
require_once __DIR__ . '/includes/class-cpt-base.php';
require_once __DIR__ . '/includes/class-doctores.php';

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
    $doctores = new \Artricenter\Content\Doctores();
    $doctores->init();
});
