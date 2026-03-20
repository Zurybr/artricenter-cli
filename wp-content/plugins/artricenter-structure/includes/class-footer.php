<?php
/**
 * Footer component for Artricenter Structure.
 *
 * @package Artricenter_Structure
 */

namespace Artricenter\Structure;

/**
 * Class Footer
 *
 * Renders the site footer with sucursales cards and info section.
 */
class Footer {

	/**
	 * Render the footer HTML.
	 *
	 * @return string Footer HTML markup.
	 */
	public function render(): string {
		$output = '<footer class="artricenter-footer">';
		$output .= '    <div class="artricenter-footer__container">';

		// Sucursales cards section.
		$output .= '        <section class="artricenter-footer__sucursales">';
		$output .= '            <h2 class="artricenter-footer__title">Nuestras Sucursales</h2>';
		$output .= '            <div class="artricenter-footer__cards">';

		// La Raza (blue).
		$output .= $this->render_card(
			'La Raza',
			'blue',
			'Calle 7 #202, Col. La Raza',
			'CP 11500, CDMX',
			'5512345678',
			'https://maps.google.com/?q=Artricenter+La+Raza'
		);

		// Atizapán (green).
		$output .= $this->render_card(
			'Atizapán',
			'green',
			'Av. Solidaridad Las Américas #45, Col. Villas de las Lomas',
			'CP 52948, Atizapán, Edo. Méx',
			'5587654321',
			'https://maps.google.com/?q=Artricenter+Atizapan'
		);

		// Viaducto (orange).
		$output .= $this->render_card(
			'Viaducto',
			'orange',
			'Viaducto Miguel Alemán #101, Col. Escandón',
			'CP 11800, CDMX',
			'5534567890',
			'https://maps.google.com/?q=Artricenter+Viaducto'
		);

		$output .= '            </div>';
		$output .= '        </section>';

		// Info section.
		$output .= '        <section class="artricenter-footer__info">';

		// Horarios.
		$output .= '            <div class="artricenter-footer__info-block">';
		$output .= '                <h3 class="artricenter-footer__info-title">Horarios</h3>';
		$output .= '                <p class="artricenter-footer__info-text">';
		$output .= '                    Martes - Sábado<br>';
		$output .= '                    8:00 - 17:00';
		$output .= '                </p>';
		$output .= '            </div>';

		// Contacto.
		$output .= '            <div class="artricenter-footer__info-block">';
		$output .= '                <h3 class="artricenter-footer__info-title">Contacto</h3>';
		$output .= '                <a href="tel:5512345678" class="artricenter-footer__info-link">';
		$output .= '                    55 1234 5678';
		$output .= '                </a>';
		$output .= '            </div>';

		// Síguenos.
		$output .= '            <div class="artricenter-footer__info-block">';
		$output .= '                <h3 class="artricenter-footer__info-title">Síguenos</h3>';
		$output .= '                <div class="artricenter-footer__social">';
		$output .= '                    <a href="https://facebook.com/artricenter" target="_blank" rel="noopener noreferrer" aria-label="Facebook">Facebook</a>';
		$output .= '                    <a href="https://twitter.com/artricenter" target="_blank" rel="noopener noreferrer" aria-label="Twitter">Twitter</a>';
		$output .= '                    <a href="https://instagram.com/artricenter" target="_blank" rel="noopener noreferrer" aria-label="Instagram">Instagram</a>';
		$output .= '                </div>';
		$output .= '            </div>';

		$output .= '        </section>';

		// Copyright.
		$output .= '        <div class="artricenter-footer__copyright">';
		$output .= '            <p>&copy; 2026 Artricenter. Todos los derechos reservados.</p>';
		$output .= '        </div>';

		$output .= '    </div>';
		$output .= '</footer>';

		return $output;
	}

	/**
	 * Render a single sucursal card.
	 *
	 * @param string $name     Sucursal name.
	 * @param string $color    Color variant (blue, green, orange).
	 * @param string $address1 Address line 1.
	 * @param string $address2 Address line 2.
	 * @param string $phone    Phone number (digits only).
	 * @param string $maps_url Google Maps URL.
	 * @return string Card HTML markup.
	 */
	private function render_card( string $name, string $color, string $address1, string $address2, string $phone, string $maps_url ): string {
		// Format phone for display.
		$phone_display = $this->format_phone( $phone );

		$output  = '<article class="artricenter-footer__card artricenter-footer__card--' . esc_attr( $color ) . '">';
		$output .= '    <div class="artricenter-footer__card-icon">';
		$output .= $this->get_location_icon();
		$output .= '    </div>';
		$output .= '    <h3 class="artricenter-footer__card-name">' . esc_html( $name ) . '</h3>';
		$output .= '    <address class="artricenter-footer__card-address">';
		$output .= '        ' . esc_html( $address1 ) . '<br>';
		$output .= '        ' . esc_html( $address2 );
		$output .= '    </address>';
		$output .= '    <a href="tel:' . esc_attr( $phone ) . '" class="artricenter-footer__card-phone">';
		$output .= '        ' . esc_html( $phone_display );
		$output .= '    </a>';
		$output .= '    <a href="' . esc_url( $maps_url ) . '"';
		$output .= '       class="artricenter-footer__card-maps"';
		$output .= '       target="_blank"';
		$output .= '       rel="noopener noreferrer">';
		$output .= '        Ver en Google Maps';
		$output .= '    </a>';
		$output .= '</article>';

		return $output;
	}

	/**
	 * Format phone number for display.
	 *
	 * @param string $phone Phone number (digits only).
	 * @return string Formatted phone number.
	 */
	private function format_phone( string $phone ): string {
		// Remove non-numeric characters.
		$phone = preg_replace( '/[^0-9]/', '', $phone );

		// Format: 55 1234 5678.
		if ( strlen( $phone ) === 10 ) {
			return substr( $phone, 0, 2 ) . ' ' . substr( $phone, 2, 4 ) . ' ' . substr( $phone, 6, 4 );
		}

		return $phone;
	}

	/**
	 * Get location icon SVG.
	 *
	 * TODO: Replace with custom location icons if needed.
	 *
	 * @return string SVG markup.
	 */
	private function get_location_icon(): string {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
	}
}
