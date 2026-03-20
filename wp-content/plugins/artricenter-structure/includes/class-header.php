<?php
/**
 * Header component class.
 *
 * @package Artricenter_Structure
 */

namespace Artricenter\Structure;

/**
 * Class Header
 *
 * Renders the site header with logo and responsive navigation.
 */
class Header {

	/**
	 * Navigation instance.
	 *
	 * @var Navigation
	 */
	private $navigation;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->navigation = new Navigation();
	}

	/**
	 * Render the header HTML.
	 *
	 * @return string Header HTML markup.
	 */
	public function render(): string {
		// Get navigation menus.
		$desktop_menu = $this->navigation->get_menu( 'artricenter_primary' );
		$mobile_menu = $this->navigation->get_menu( 'artricenter_mobile' );

		ob_start();
		?>
		<!-- Sticky header: position: sticky; top: 0; z-index: 50 -->
		<header class="artricenter-header">
			<div class="artricenter-header__container">
				<!-- Mobile: Hamburger + Logo + Spacer -->
				<div class="artricenter-header__mobile">
					<button class="artricenter-menu-toggle" aria-label="Abrir menú" aria-expanded="false">
						<span class="hamburger-line"></span>
						<span class="hamburger-line"></span>
						<span class="hamburger-line"></span>
					</button>
					<span class="artricenter-header__title">Artricenter</span>
				</div>

				<!-- Desktop: Logo + Nav -->
				<div class="artricenter-header__desktop">
					<a href="/" class="artricenter-header__logo">
						<!-- TODO: Copy logo.png from Astro site to WordPress assets -->
						<img src="/assets/logo.png" alt="Artricenter Logo">
					</a>
					<nav class="artricenter-nav">
						<?php echo $desktop_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</nav>
				</div>
			</div>

			<!-- Mobile overlay menu -->
			<div class="artricenter-menu-overlay">
				<div class="artricenter-menu-overlay__content">
					<button class="artricenter-menu-close" aria-label="Cerrar menú">×</button>
					<nav class="artricenter-nav--mobile">
						<?php echo $mobile_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</nav>
				</div>
			</div>
		</header>
		<?php
		return ob_get_clean();
	}
}
