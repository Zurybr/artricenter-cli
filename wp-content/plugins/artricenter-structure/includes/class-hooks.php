<?php
/**
 * Hooks registration class.
 *
 * Registers custom WordPress hooks for content injection.
 *
 * @package Artricenter_Structure
 * @since 1.0.0
 */

namespace Artricenter\Structure;

/**
 * Hooks class.
 *
 * Registers custom hooks that fire before and after the main content area,
 * allowing other plugins to inject content without modifying theme templates.
 *
 * @since 1.0.0
 */
class Hooks {

	/**
	 * Register custom hooks for content injection.
	 *
	 * Registers hooks that fire before and after the main content area,
	 * allowing other plugins to inject content without modifying theme templates.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks(): void {
		// Fire hooks on the 'wp' action (after WordPress is fully loaded).
		add_action( 'wp', array( $this, 'fire_before_content' ), 10 );
		add_action( 'wp', array( $this, 'fire_after_content' ), 10 );
	}

	/**
	 * Fire the 'artricenter_before_content' hook.
	 *
	 * This hook fires before the main content area, allowing plugins
	 * to inject banners, announcements, or other content.
	 *
	 * Example usage:
	 * ```php
	 * add_action( 'artricenter_before_content', 'my_custom_banner' );
	 * function my_custom_banner() {
	 *     echo '<div class="custom-banner">Welcome!</div>';
	 * }
	 * ```
	 *
	 * @since 1.0.0
	 */
	public function fire_before_content(): void {
		do_action( 'artricenter_before_content' );
	}

	/**
	 * Fire the 'artricenter_after_content' hook.
	 *
	 * This hook fires after the main content area, allowing plugins
	 * to inject CTAs, related content, or other elements.
	 *
	 * Example usage:
	 * ```php
	 * add_action( 'artricenter_after_content', 'my_custom_cta' );
	 * function my_custom_cta() {
	 *     echo '<div class="custom-cta">Contact us today!</div>';
	 * }
	 * ```
	 *
	 * @since 1.0.0
	 */
	public function fire_after_content(): void {
		do_action( 'artricenter_after_content' );
	}
}
