<?php
/**
 * Doctores CPT Class
 *
 * Custom Post Type for managing doctor profiles.
 *
 * @package Artricenter\Content
 */

namespace Artricenter\Content;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Doctores extends CPT_Base {

    /**
     * Get the post type slug.
     *
     * @return string Post type slug.
     */
    public function get_post_type(): string {
        return 'doctor';
    }

    /**
     * Get CPT labels.
     *
     * @return array CPT labels.
     */
    public function get_labels(): array {
        return [
            'name'                  => __('Doctores', 'artricenter-content'),
            'singular_name'         => __('Doctor', 'artricenter-content'),
            'menu_name'             => __('Doctores', 'artricenter-content'),
            'add_new'               => __('Agregar Doctor', 'artricenter-content'),
            'add_new_item'          => __('Agregar Nuevo Doctor', 'artricenter-content'),
            'edit_item'             => __('Editar Doctor', 'artricenter-content'),
            'new_item'              => __('Nuevo Doctor', 'artricenter-content'),
            'view_item'             => __('Ver Doctor', 'artricenter-content'),
            'view_items'            => __('Ver Doctores', 'artricenter-content'),
            'search_items'          => __('Buscar Doctores', 'artricenter-content'),
            'not_found'             => __('No se encontraron doctores', 'artricenter-content'),
            'not_found_in_trash'    => __('No se encontraron doctores en la papelera', 'artricenter-content'),
            'parent_item_colon'     => __('Doctor Padre:', 'artricenter-content'),
            'all_items'             => __('Todos los Doctores', 'artricenter-content'),
            'archives'              => __('Archivo de Doctores', 'artricenter-content'),
            'insert_into_item'      => __('Insertar en doctor', 'artricenter-content'),
            'uploaded_to_this_item' => __('Subido a este doctor', 'artricenter-content'),
            'featured_image'        => __('Foto del Doctor', 'artricenter-content'),
            'set_featured_image'    => __('Establecer foto del doctor', 'artricenter-content'),
            'remove_featured_image' => __('Eliminar foto del doctor', 'artricenter-content'),
            'use_featured_image'    => __('Usar como foto del doctor', 'artricenter-content'),
        ];
    }

    /**
     * Get CPT arguments.
     *
     * @return array CPT arguments.
     */
    public function get_args(): array {
        return [
            'public'            => true,
            'has_archive'       => false,
            'rewrite'           => ['slug' => 'doctor-artricenter'],
            'supports'          => ['title', 'thumbnail', 'page-attributes'],
            'menu_icon'         => 'dashicons-groups',
            'show_in_rest'      => true,
            'capability_type'   => 'post',
            'map_meta_cap'      => true,
            'menu_position'     => 20,
        ];
    }

    /**
     * Add meta boxes for Doctores CPT.
     *
     * @return void
     */
    public function add_meta_boxes(): void {
        add_meta_box(
            'doctor_basic_info',
            __('Información Básica', 'artricenter-content'),
            [$this, 'render_basic_info_meta_box'],
            'doctor',
            'normal',
            'high'
        );

        add_meta_box(
            'doctor_social_media',
            __('Redes Sociales', 'artricenter-content'),
            [$this, 'render_social_media_meta_box'],
            'doctor',
            'normal',
            'default'
        );
    }

    /**
     * Render basic info meta box.
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function render_basic_info_meta_box(\WP_Post $post): void {
        // Add nonce field
        wp_nonce_field('artricenter_save_meta', 'artricenter_meta_nonce');

        // Retrieve current values
        $specialty = get_post_meta($post->ID, '_doctor_specialty', true);
        $location = get_post_meta($post->ID, '_doctor_location', true);

        // Enqueue media scripts for image uploader
        wp_enqueue_media();

        ?>
        <style>
            .artricenter-field {
                margin-bottom: 15px;
            }
            .artricenter-field label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .artricenter-field input[type="text"],
            .artricenter-field select,
            .artricenter-field textarea {
                width: 100%;
                padding: 8px;
            }
            .artricenter-field .description {
                font-size: 12px;
                color: #666;
                margin-top: 3px;
            }
        </style>

        <div class="artricenter-field">
            <label for="doctor_specialty"><?php _e('Especialidad', 'artricenter-content'); ?></label>
            <input type="text"
                   id="doctor_specialty"
                   name="doctor_specialty"
                   value="<?php echo esc_attr($specialty); ?>"
                   placeholder="<?php _e('Ej: Cardiología, Pediatría, etc.', 'artricenter-content'); ?>">
            <p class="description"><?php _e('La especialidad médica del doctor.', 'artricenter-content'); ?></p>
        </div>

        <div class="artricenter-field">
            <label for="doctor_location"><?php _e('Ubicación', 'artricenter-content'); ?></label>
            <select id="doctor_location" name="doctor_location">
                <option value=""><?php _e('Seleccionar ubicación', 'artricenter-content'); ?></option>
                <option value="central" <?php selected($location, 'central'); ?>><?php _e('Central', 'artricenter-content'); ?></option>
                <option value="norte" <?php selected($location, 'norte'); ?>><?php _e('Norte', 'artricenter-content'); ?></option>
                <option value="sur" <?php selected($location, 'sur'); ?>><?php _e('Sur', 'artricenter-content'); ?></option>
            </select>
            <p class="description"><?php _e('La sucursal donde atiende el doctor.', 'artricenter-content'); ?></p>
        </div>
        <?php
    }

    /**
     * Render social media meta box.
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function render_social_media_meta_box(\WP_Post $post): void {
        // Retrieve current values
        $facebook = get_post_meta($post->ID, '_doctor_facebook', true);
        $twitter = get_post_meta($post->ID, '_doctor_twitter', true);
        $linkedin = get_post_meta($post->ID, '_doctor_linkedin', true);

        ?>
        <style>
            .artricenter-field {
                margin-bottom: 15px;
            }
            .artricenter-field label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .artricenter-field input[type="url"] {
                width: 100%;
                padding: 8px;
            }
            .artricenter-field .description {
                font-size: 12px;
                color: #666;
                margin-top: 3px;
            }
        </style>

        <div class="artricenter-field">
            <label for="doctor_facebook"><?php _e('Facebook URL', 'artricenter-content'); ?></label>
            <input type="url"
                   id="doctor_facebook"
                   name="doctor_facebook"
                   value="<?php echo esc_attr($facebook); ?>"
                   placeholder="https://facebook.com/username">
            <p class="description"><?php _e('Perfil de Facebook del doctor.', 'artricenter-content'); ?></p>
        </div>

        <div class="artricenter-field">
            <label for="doctor_twitter"><?php _e('Twitter URL', 'artricenter-content'); ?></label>
            <input type="url"
                   id="doctor_twitter"
                   name="doctor_twitter"
                   value="<?php echo esc_attr($twitter); ?>"
                   placeholder="https://twitter.com/username">
            <p class="description"><?php _e('Perfil de Twitter del doctor.', 'artricenter-content'); ?></p>
        </div>

        <div class="artricenter-field">
            <label for="doctor_linkedin"><?php _e('LinkedIn URL', 'artricenter-content'); ?></label>
            <input type="url"
                   id="doctor_linkedin"
                   name="doctor_linkedin"
                   value="<?php echo esc_attr($linkedin); ?>"
                   placeholder="https://linkedin.com/in/username">
            <p class="description"><?php _e('Perfil de LinkedIn del doctor.', 'artricenter-content'); ?></p>
        </div>
        <?php
    }

    /**
     * Save meta fields.
     *
     * @param int $post_id Post ID.
     * @param array $data POST data.
     * @return void
     */
    protected function save_meta_fields(int $post_id, array $data): void {
        // Save specialty (text field)
        if (isset($data['doctor_specialty'])) {
            $sanitized = $this->sanitize_field($data['doctor_specialty'], 'text');
            update_post_meta($post_id, '_doctor_specialty', $sanitized);
        }

        // Save location (text field - dropdown value)
        if (isset($data['doctor_location'])) {
            $sanitized = $this->sanitize_field($data['doctor_location'], 'text');
            update_post_meta($post_id, '_doctor_location', $sanitized);
        }

        // Save Facebook URL
        if (isset($data['doctor_facebook'])) {
            $sanitized = $this->sanitize_field($data['doctor_facebook'], 'url');
            update_post_meta($post_id, '_doctor_facebook', $sanitized);
        }

        // Save Twitter URL
        if (isset($data['doctor_twitter'])) {
            $sanitized = $this->sanitize_field($data['doctor_twitter'], 'url');
            update_post_meta($post_id, '_doctor_twitter', $sanitized);
        }

        // Save LinkedIn URL
        if (isset($data['doctor_linkedin'])) {
            $sanitized = $this->sanitize_field($data['doctor_linkedin'], 'url');
            update_post_meta($post_id, '_doctor_linkedin', $sanitized);
        }
    }
}
