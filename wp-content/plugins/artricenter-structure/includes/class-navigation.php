<?php
/**
 * Navigation menu registration class.
 *
 * @package Artricenter_Structure
 */

namespace Artricenter\Structure;

/**
 * Class Navigation
 *
 * Registers menu locations and provides menu display functionality.
 */
class Navigation {

	/**
	 * Register navigation menu locations.
	 *
	 * @return void
	 */
	public function register_menus(): void {
		register_nav_menus(
			array(
				'artricenter_primary' => __( 'Artricenter Primary Menu', 'artricenter-structure' ),
				'artricenter_mobile'  => __( 'Artricenter Mobile Menu', 'artricenter-structure' ),
			)
		);
	}

	/**
	 * Get menu HTML for a specific location.
	 *
	 * @param string $location The menu location identifier.
	 * @return string Menu HTML markup or empty string if no menu assigned.
	 */
	public function get_menu( string $location ): string {
		$args = array(
			'theme_location' => $location,
			'container'      => false,           // No container div.
			'menu_class'     => 'artricenter-nav__menu',
			'fallback_cb'    => false,           // Don't show pages if menu not set.
			'echo'           => false,           // Return instead of echo.
		);

		/**
		 * Filter menu arguments.
		 *
		 * @param array  $args     Menu arguments.
		 * @param string $location Menu location.
		 */
		$args = apply_filters( 'artricenter_nav_menu_args', $args, $location );

		return wp_nav_menu( $args );
	}
}
