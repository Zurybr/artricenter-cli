<?php
/**
 * Template Tags
 *
 * Functions for displaying Artricenter structure components in themes.
 *
 * Usage in theme templates:
 * - Header: <?php artricenter_the_header(); ?>
 * - Footer: <?php artricenter_the_footer(); ?>
 *
 * @package Artricenter_Structure
 */

/**
 * Retrieve the Artricenter header HTML.
 *
 * @return string Header HTML markup.
 */
function artricenter_get_header(): string {
	$header = new \Artricenter\Structure\Header();
	return $header->render();
}

/**
 * Display the Artricenter header.
 *
 * Usage in theme: <?php artricenter_the_header(); ?>
 *
 * @return void
 */
function artricenter_the_header(): void {
	echo artricenter_get_header();
}

/**
 * Retrieve the Artricenter footer HTML.
 *
 * @return string Footer HTML markup.
 */
function artricenter_get_footer(): string {
	$footer = new \Artricenter\Structure\Footer();
	return $footer->render();
}

/**
 * Display the Artricenter footer.
 *
 * Usage in theme: <?php artricenter_the_footer(); ?>
 *
 * @return void
 */
function artricenter_the_footer(): void {
	echo artricenter_get_footer();
}

/**
 * Enqueue JavaScript assets.
 *
 * @return void
 */
function artricenter_enqueue_scripts(): void {
	wp_enqueue_script(
		'artricenter-mobile-menu',
		plugins_url( 'assets/js/mobile-menu.js', __FILE__ ),
		array(),                    // No dependencies.
		'1.0.0',
		true                        // Load in footer.
	);
}
add_action( 'wp_enqueue_scripts', 'artricenter_enqueue_scripts' );

/**
 * Enqueue plugin stylesheets.
 *
 * @return void
 */
function artricenter_enqueue_styles(): void {
	// Enqueue variables first (dependencies)
	wp_enqueue_style(
		'artricenter-structure-variables',
		plugins_url( 'assets/css/variables.css', __FILE__ ),
		array(),
		'1.0.0',
		'all'
	);

	// Enqueue main styles (depends on variables)
	wp_enqueue_style(
		'artricenter-structure-style',
		plugins_url( 'assets/css/main.css', __FILE__ ),
		array( 'artricenter-structure-variables' ),
		'1.0.0',
		'all'
	);
}
add_action( 'wp_enqueue_scripts', 'artricenter_enqueue_styles' );

/**
 * Register navigation menu locations.
 *
 * @return void
 */
function artricenter_register_menus(): void {
	$navigation = new \Artricenter\Structure\Navigation();
	$navigation->register_menus();
}
add_action( 'after_setup_theme', 'artricenter_register_menus' );
