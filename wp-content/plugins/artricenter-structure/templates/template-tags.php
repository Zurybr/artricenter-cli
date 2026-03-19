<?php
/**
 * Template tag functions for Artricenter Structure.
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
 * Register navigation menu locations.
 *
 * @return void
 */
function artricenter_register_menus(): void {
	$navigation = new \Artricenter\Structure\Navigation();
	$navigation->register_menus();
}
add_action( 'after_setup_theme', 'artricenter_register_menus' );
