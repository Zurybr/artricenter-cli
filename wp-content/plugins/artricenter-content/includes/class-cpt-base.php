<?php
/**
 * CPT Base Class
 *
 * Abstract base class for Custom Post Type functionality.
 * Provides common methods for CPT registration and meta box handling.
 *
 * @package Artricenter\Content
 */

namespace Artricenter\Content;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

abstract class CPT_Base {

    /**
     * Get the post type slug.
     *
     * @return string Post type slug.
     */
    abstract public function get_post_type(): string;

    /**
     * Get CPT labels.
     *
     * @return array CPT labels.
     */
    abstract public function get_labels(): array;

    /**
     * Get CPT arguments.
     *
     * @return array CPT arguments.
     */
    abstract public function get_args(): array;

    /**
     * Register the custom post type.
     *
     * @return void
     */
    public function register(): void {
        register_post_type(
            $this->get_post_type(),
            array_merge(
                ['labels' => $this->get_labels()],
                $this->get_args()
            )
        );
    }

    /**
     * Add meta boxes for this CPT.
     *
     * @return void
     */
    abstract public function add_meta_boxes(): void;

    /**
     * Save meta box data.
     *
     * Handles nonce verification, capability checks, and autosave prevention.
     *
     * @param int $post_id Post ID.
     * @param array $data POST data.
     * @return void
     */
    public function save_meta_box(int $post_id, array $data): void {
        // Nonce verification
        if (!isset($data['artricenter_meta_nonce']) ||
            !wp_verify_nonce($data['artricenter_meta_nonce'], 'artricenter_save_meta')) {
            return;
        }

        // Capability check
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Autosave check
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $this->save_meta_fields($post_id, $data);
    }

    /**
     * Save meta fields.
     *
     * Abstract method for saving specific meta fields.
     *
     * @param int $post_id Post ID.
     * @param array $data POST data.
     * @return void
     */
    abstract protected function save_meta_fields(int $post_id, array $data): void;

    /**
     * Sanitize a field value.
     *
     * @param string $value Field value.
     * @param string $type Field type (text, url, email).
     * @return string Sanitized value.
     */
    protected function sanitize_field(string $value, string $type = 'text'): string {
        switch ($type) {
            case 'url':
                return esc_url_raw($value);
            case 'email':
                return sanitize_email($value);
            case 'text':
            default:
                return sanitize_text_field($value);
        }
    }

    /**
     * Initialize CPT hooks.
     *
     * @return void
     */
    public function init(): void {
        // Register post type
        add_action('init', [$this, 'register']);

        // Add meta boxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);

        // Save meta box data
        add_action('save_post', [$this, 'save_post_handler']);
    }

    /**
     * Save post handler.
     *
     * Wrapper for save_meta_box that hooks into save_post.
     *
     * @param int $post_id Post ID.
     * @return void
     */
    public function save_post_handler(int $post_id): void {
        // Don't save on revisions or autosaves
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }

        // Check if this is the correct post type
        if (isset($_POST['post_type']) && $this->get_post_type() === $_POST['post_type']) {
            $this->save_meta_box($post_id, $_POST);
        }
    }
}
