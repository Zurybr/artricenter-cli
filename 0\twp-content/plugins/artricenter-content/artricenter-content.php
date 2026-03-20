<?php
/**
 * Page Creator Class
 *
 * Automatically creates WordPress pages on plugin activation
 *
 * @package Artricenter_Content
 */

namespace Artricenter\Content;

/**
 * Page_Creator Class
 *
 * Handles programmatic creation of WordPress pages with assigned templates
 */
class Page_Creator {

	/**
	 * Pages to create on activation
	 *
	 * @var array
	 */
	private $pages = array(
		'inicio'                  => array(
			'title'    => 'Inicio',
			'template' => 'page-homepage.php',
			'content'  => '<!-- wp:paragraph -->
<p>Bienvenido a Artricenter, tu centro de atención médica integral.</p>
<!-- /wp:paragraph -->',
		),
		'especialidades'          => array(
			'title'    => 'Especialidades',
			'template' => 'page-especialidades.php',
			'content'  => '<!-- wp:paragraph -->
<p>Conoce nuestras especialidades médicas.</p>
<!-- /wp:paragraph -->',
		),
		'tratamiento-medico-integral' => array(
			'title'    => 'Tratamiento Médico Integral',
			'template' => 'page-tratamiento.php',
			'content'  => '<!-- wp:paragraph -->
<p>Programa PAIPER de atención integral.</p>
<!-- /wp:paragraph -->',
		),
		'club-vida-y-salud'       => array(
			'title'    => 'Club de Vida y Salud',
			'template' => 'page-club-vida.php',
			'content'  => '<!-- wp:paragraph -->
<p>Únete a nuestro club de membresía exclusiva.</p>
<!-- /wp:paragraph -->',
		),
		'contacto'                => array(
			'title'    => 'Contacto',
			'template' => 'page-contacto.php',
			'content'  => '<!-- wp:paragraph -->
<p>Contáctanos para más información.</p>
<!-- /wp:paragraph -->',
		),
	);

	/**
	 * Create all pages if they don't exist
	 *
	 * @return void
	 */
	public function create_pages(): void {
		foreach ( $this->pages as $slug => $page_data ) {
			$this->create_page_if_not_exists( $slug, $page_data );
		}
	}

	/**
	 * Create a single page if it doesn't already exist
	 *
	 * @param string $slug      The page slug.
	 * @param array  $page_data Page data (title, template, content).
	 * @return void
	 */
	private function create_page_if_not_exists( string $slug, array $page_data ): void {
		// Check if page already exists
		$existing = get_page_by_path( $slug );

		if ( $existing ) {
			return; // Skip if exists
		}

		// Create page
		$page_id = wp_insert_post(
			array(
				'post_title'   => $page_data['title'],
				'post_content' => $page_data['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_name'    => $slug,
			)
		);

		if ( is_wp_error( $page_id ) ) {
			error_log( 'Failed to create page: ' . $page_data['title'] . ' - ' . $page_id->get_error_message() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			return;
		}

		// Assign template
		update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
	}
}
