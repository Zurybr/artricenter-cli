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
