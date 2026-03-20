<?php
/**
 * Shortcodes Class
 *
 * Handles shortcode registration and rendering for CPT data display.
 *
 * @package Artricenter\Content
 */

namespace Artricenter\Content;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Shortcodes {

    /**
     * Register all shortcodes.
     *
     * @return void
     */
    public function register(): void {
        add_shortcode('artricenter_doctores_grid', [$this, 'render_doctores_grid']);
        add_shortcode('artricenter_mission_cards', [$this, 'render_mission_cards']);
        add_shortcode('especialidades_list', [$this, 'render_especialidades_list']);
    }

    /**
     * Render doctors grid shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Rendered output.
     */
    public function render_doctores_grid($atts): string {
        $atts = shortcode_atts([
            'limit' => 3,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ], $atts);

        $query = new \WP_Query([
            'post_type' => 'doctor',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        ]);

        if (!$query->have_posts()) {
            return '';
        }

        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/shortcodes/doctores-grid.php';
        return ob_get_clean();
    }

    /**
     * Render mission/vision/values cards shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Rendered output.
     */
    public function render_mission_cards($atts): string {
        // Mission/vision/values hardcoded or from options
        // For now, render hardcoded cards
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/shortcodes/mission-cards.php';
        return ob_get_clean();
    }

    /**
     * Render specialties list shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Rendered output.
     */
    public function render_especialidades_list($atts): string {
        $atts = shortcode_atts([
            'limit' => -1, // All specialties
        ], $atts);

        $query = new \WP_Query([
            'post_type' => 'especialidad',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        if (!$query->have_posts()) {
            return '';
        }

        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/shortcodes/especialidades-list.php';
        return ob_get_clean();
    }
}
