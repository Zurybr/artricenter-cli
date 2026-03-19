/**
 * Mobile menu functionality for Artricenter.
 *
 * Handles menu toggle, close actions, keyboard accessibility,
 * and body scroll lock when menu is open.
 */
(function() {
	'use strict';

	const menuToggle = document.querySelector('.artricenter-menu-toggle');
	const menuClose = document.querySelector('.artricenter-menu-close');
	const menuOverlay = document.querySelector('.artricenter-menu-overlay');
	const body = document.body;

	/**
	 * Open mobile menu.
	 */
	function openMenu() {
		if (menuOverlay) {
			menuOverlay.classList.add('artricenter-menu-overlay--open');
		}
		if (menuToggle) {
			menuToggle.setAttribute('aria-expanded', 'true');
		}
		// Prevent background scrolling.
		body.style.overflow = 'hidden';
	}

	/**
	 * Close mobile menu.
	 */
	function closeMenu() {
		if (menuOverlay) {
			menuOverlay.classList.remove('artricenter-menu-overlay--open');
		}
		if (menuToggle) {
			menuToggle.setAttribute('aria-expanded', 'false');
		}
		// Restore background scrolling.
		body.style.overflow = '';
	}

	// Open menu when hamburger is clicked.
	if (menuToggle) {
		menuToggle.addEventListener('click', function(e) {
			e.preventDefault();
			openMenu();
		});
	}

	// Close menu when close button is clicked.
	if (menuClose) {
		menuClose.addEventListener('click', function(e) {
			e.preventDefault();
			closeMenu();
		});
	}

	// Close menu when backdrop is clicked.
	if (menuOverlay) {
		menuOverlay.addEventListener('click', function(e) {
			if (e.target === menuOverlay) {
				closeMenu();
			}
		});
	}

	// Close menu on Escape key (accessibility).
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape' && menuOverlay && menuOverlay.classList.contains('artricenter-menu-overlay--open')) {
			closeMenu();
		}
	});

	/**
	 * Handle submenu accordions (if menu has children).
	 * Adds click listeners to submenu toggle buttons.
	 */
	function initSubmenuAccordions() {
		const submenuToggles = document.querySelectorAll('.artricenter-nav__menu .menu-item-has-children > a');

		submenuToggles.forEach(function(link) {
			// Create toggle button.
			const toggle = document.createElement('button');
			toggle.setAttribute('aria-expanded', 'false');
			toggle.classList.add('artricenter-submenu-toggle');
			toggle.innerHTML = '+';

			// Insert toggle after link.
			link.parentNode.insertBefore(toggle, link.nextSibling);

			// Toggle submenu on click.
			toggle.addEventListener('click', function(e) {
				e.preventDefault();
				const submenu = link.nextElementSibling.nextElementSibling;

				if (submenu && submenu.classList.contains('sub-menu')) {
					const isOpen = submenu.classList.contains('artricenter-submenu--open');

					// Close all other submenus.
					document.querySelectorAll('.artricenter-submenu--open').forEach(function(openSubmenu) {
						openSubmenu.classList.remove('artricenter-submenu--open');
					});

					document.querySelectorAll('.artricenter-submenu-toggle[aria-expanded="true"]').forEach(function(openToggle) {
						openToggle.setAttribute('aria-expanded', 'false');
						openToggle.innerHTML = '+';
					});

					// Toggle current submenu.
					if (!isOpen) {
						submenu.classList.add('artricenter-submenu--open');
						toggle.setAttribute('aria-expanded', 'true');
						toggle.innerHTML = '−';
					}
				}
			});
		});
	}

	// Initialize submenu accordions after DOM is ready.
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initSubmenuAccordions);
	} else {
		initSubmenuAccordions();
	}

})();
