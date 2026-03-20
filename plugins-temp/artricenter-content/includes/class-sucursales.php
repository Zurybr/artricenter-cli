<?php
/**
 * Sucursales Custom Post Type
 *
 * Manages clinic location information for Artricenter.
 *
 * @package Artricenter\Content
 */

namespace Artricenter\Content;

class Sucursales extends CPT_Base {

    /**
     * Get the post type slug
     *
     * @return string Post type slug
     */
    public function get_post_type(): string {
        return 'sucursal';
    }

    /**
     * Get CPT labels
     *
     * @return array CPT labels array
     */
    public function get_labels(): array {
        return [
            'name' => 'Sucursales',
            'singular_name' => 'Sucursal',
            'menu_name' => 'Sucursales',
            'add_new' => 'Agregar Sucursal',
            'edit_item' => 'Editar Sucursal',
            'view_item' => 'Ver Sucursal',
            'search_items' => 'Buscar Sucursales',
            'not_found' => 'No se encontraron sucursales',
            'not_found_in_trash' => 'No hay sucursales en la papelera',
            'all_items' => 'Todas las Sucursales',
            'add_new_item' => 'Agregar Nueva Sucursal',
            'new_item' => 'Nueva Sucursal',
            'view_items' => 'Ver Sucursales',
            'parent_item_colon' => 'Sucursal Padre:',
            'archives' => 'Archivo de Sucursales',
            'attributes' => 'Atributos de Sucursal',
            'insert_into_item' => 'Insertar en sucursal',
            'uploaded_to_this_item' => 'Subidos a esta sucursal',
            'filter_items_list' => 'Filtrar lista de sucursales',
            'items_list_navigation' => 'Navegación de lista de sucursales',
            'items_list' => 'Lista de sucursales',
        ];
    }

    /**
     * Get CPT registration arguments
     *
     * @return array CPT arguments array
     */
    public function get_args(): array {
        return [
            'public' => true,
            'has_archive' => false,
            'rewrite' => ['slug' => 'sucursal-artricenter'],
            'supports' => ['title', 'page-attributes'],
            'menu_icon' => 'dashicons-location',
            'show_in_rest' => true,
            'description' => 'Ubicaciones de clínicas Artricenter',
            'menu_position' => 6,
        ];
    }

    /**
     * Add meta boxes for this CPT
     *
     * @return void
     */
    public function add_meta_boxes(): void {
        add_meta_box(
            'sucursal_location',
            'Información de Ubicación',
            [$this, 'render_location_meta_box'],
            'sucursal',
            'normal',
            'high'
        );
    }

    /**
     * Render location meta box
     *
     * @param \WP_Post $post Post object
     * @return void
     */
    public function render_location_meta_box(\WP_Post $post): void {
        wp_nonce_field('artricenter_save_meta', 'artricenter_meta_nonce');

        $address = get_post_meta($post->ID, '_sucursal_address', true);
        $phone = get_post_meta($post->ID, '_sucursal_phone', true);
        $maps_link = get_post_meta($post->ID, '_sucursal_maps_link', true);
        $color_scheme = get_post_meta($post->ID, '_sucursal_color', true);

        echo '<table class="form-table">';

        // Address (textarea)
        echo '<tr><th><label for="sucursal_address">Dirección</label></th>';
        echo '<td><textarea name="sucursal_address" id="sucursal_address" rows="3" class="large-text">' .
            esc_textarea($address) . '</textarea></td></tr>';

        // Phone (text)
        echo '<tr><th><label for="sucursal_phone">Teléfono</label></th>';
        echo '<td><input type="text" name="sucursal_phone" id="sucursal_phone" value="' .
            esc_attr($phone) . '" class="regular-text"></td></tr>';

        // Google Maps Link (URL)
        echo '<tr><th><label for="sucursal_maps_link">Google Maps Link</label></th>';
        echo '<td><input type="url" name="sucursal_maps_link" id="sucursal_maps_link" value="' .
            esc_url($maps_link) . '" class="large-text">';
        echo '<p class="description">Enlace completo de Google Maps (https://maps.google.com/...)</p></td></tr>';

        // Color Scheme (select dropdown)
        echo '<tr><th><label for="sucursal_color">Esquema de Color</label></th>';
        echo '<td><select name="sucursal_color" id="sucursal_color">';
        echo '<option value="">Seleccionar color...</option>';
        echo '<option value="blue"' . selected($color_scheme, 'blue', false) . '>Azul</option>';
        echo '<option value="green"' . selected($color_scheme, 'green', false) . '>Verde</option>';
        echo '<option value="orange"' . selected($color_scheme, 'orange', false) . '>Naranja</option>';
        echo '</select>';
        echo '<p class="description">Color para identificar esta sucursal</p></td></tr>';

        echo '</table>';
    }

    /**
     * Save specific meta fields for this CPT
     *
     * @param int $post_id Post ID
     * @param array $data POST data
     * @return void
     */
    protected function save_meta_fields(int $post_id, array $data): void {
        // Save _sucursal_address (sanitize_textarea_field)
        if (isset($data['sucursal_address'])) {
            update_post_meta($post_id, '_sucursal_address',
                sanitize_textarea_field($data['sucursal_address']));
        }

        // Save _sucursal_phone (sanitize_text_field)
        if (isset($data['sucursal_phone'])) {
            update_post_meta($post_id, '_sucursal_phone',
                $this->sanitize_field($data['sucursal_phone']));
        }

        // Save _sucursal_maps_link (esc_url_raw)
        if (isset($data['sucursal_maps_link'])) {
            update_post_meta($post_id, '_sucursal_maps_link',
                $this->sanitize_field($data['sucursal_maps_link'], 'url'));
        }

        // Save _sucursal_color (sanitize_text_field with whitelist)
        if (isset($data['sucursal_color'])) {
            $valid_colors = ['blue', 'green', 'orange'];
            $color = sanitize_text_field($data['sucursal_color']);
            if (in_array($color, $valid_colors, true)) {
                update_post_meta($post_id, '_sucursal_color', $color);
            }
        }
    }
}
