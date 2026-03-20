<?php
namespace Artricenter\Content;

abstract class CPT_Base {
    abstract public function get_post_type(): string;
    abstract public function get_labels(): array;
    abstract public function get_args(): array;

    public function register(): void {
        register_post_type(
            $this->get_post_type(),
            array_merge(
                ['labels' => $this->get_labels()],
                $this->get_args()
            )
        );
    }

    abstract public function add_meta_boxes(): void;

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

    abstract protected function save_meta_fields(int $post_id, array $data): void;

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
}
