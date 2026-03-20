<?php
namespace Artricenter\Content;

class Especialidades extends CPT_Base {
    public function get_post_type(): string {
        return 'especialidad';
    }

    public function get_labels(): array {
        return [
            'name' => 'Especialidades',
            'singular_name' => 'Especialidad',
            'menu_name' => 'Especialidades',
            'add_new' => 'Agregar Especialidad',
            'edit_item' => 'Editar Especialidad',
            'view_item' => 'Ver Especialidad',
            'search_items' => 'Buscar Especialidades',
            'not_found' => 'No se encontraron especialidades',
            'not_found_in_trash' => 'No hay especialidades en la papelera',
            'all_items' => 'Todas las Especialidades',
            'add_new_item' => 'Agregar Nueva Especialidad',
            'new_item' => 'Nueva Especialidad',
            'view_items' => 'Ver Especialidades',
            'parent_item_colon' => 'Especialidad Padre:',
            'archives' => 'Archivo de Especialidades',
            'attributes' => 'Atributos de Especialidad',
            'insert_into_item' => 'Insertar en especialidad',
            'uploaded_to_this_item' => 'Subidos a esta especialidad',
            'filter_items_list' => 'Filtrar lista de especialidades',
            'items_list_navigation' => 'Navegación de lista de especialidades',
            'items_list' => 'Lista de especialidades',
        ];
    }

    public function get_args(): array {
        return [
            'public' => true,
            'has_archive' => false,
            'rewrite' => ['slug' => 'especialidad-artricenter'],
            'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
            'menu_icon' => 'dashicons-heart',
            'show_in_rest' => true,
            'description' => 'Especialidades médicas de Artricenter',
            'menu_position' => 5,
        ];
    }

    public function add_meta_boxes(): void {
        add_meta_box(
            'especialidad_info',
            'Información de Especialidad',
            [$this, 'render_info_meta_box'],
            'especialidad',
            'normal',
            'high'
        );
    }

    public function render_info_meta_box(\WP_Post $post): void {
        wp_nonce_field('artricenter_save_meta', 'artricenter_meta_nonce');

        echo '<div class="artricenter-meta-box">';
        echo '<p><strong>Campos de la Especialidad:</strong></p>';
        echo '<ul>';
        echo '<li><strong>Nombre:</strong> Use el campo de título arriba para el nombre de la especialidad.</li>';
        echo '<li><strong>Descripción:</strong> Use el editor de contenido para la descripción detallada.</li>';
        echo '<li><strong>Imagen/Icono:</strong> Use la imagen destacada (a la derecha) para el icono o representación visual.</li>';
        echo '</ul>';
        echo '</div>';
    }

    protected function save_meta_fields(int $post_id, array $data): void {
        // Most fields use WordPress built-in features:
        // - Name: post_title
        // - Description: post_content (editor)
        // - Image: post_thumbnail (featured image)

        // No custom meta fields needed unless adding additional fields
    }
}
