/**
 * File navigation.js.
 *
 * Header behavior: mobile toggle, mobile submenu toggle, scrolled state,
 * outside-click close. Vanilla JS only.
 */
document.addEventListener('DOMContentLoaded', function () {
	var header = document.querySelector('.site-header');
	if (!header) {
		return;
	}

	var toggle = header.querySelector('.menu-toggle');
	var nav = header.querySelector('.main-navigation');
	var primaryMenu = header.querySelector('#primary-menu');
	var MOBILE_BREAKPOINT = 767;

	function isMobile() {
		return window.innerWidth <= MOBILE_BREAKPOINT;
	}

	function closeMobileMenu() {
		if (nav && nav.classList.contains('toggled')) {
			nav.classList.remove('toggled');
		}
		if (toggle) {
			toggle.setAttribute('aria-expanded', 'false');
		}
	}

	// 1. Mobile menu toggle
	if (toggle && nav) {
		toggle.addEventListener('click', function (e) {
			e.stopPropagation();
			var willOpen = !nav.classList.contains('toggled');
			nav.classList.toggle('toggled');
			toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
		});
	}

	// Close mobile menu when clicking a link, except on submenu parents
	if (primaryMenu) {
		var links = primaryMenu.querySelectorAll('a');
		for (var i = 0; i < links.length; i++) {
			links[i].addEventListener('click', function (e) {
				var parentLi = this.parentNode;
				var isSubmenuParent = parentLi && parentLi.classList.contains('menu-item-has-children');
				var href = this.getAttribute('href');

				// 2. Mobile submenu toggle: parent with href="#"
				if (isMobile() && isSubmenuParent && href === '#') {
					e.preventDefault();
					parentLi.classList.toggle('open');
					return;
				}

				if (!isSubmenuParent) {
					closeMobileMenu();
				}
			});
		}
	}

	// 3. Header scrolled state
	function updateScrolled() {
		if (window.scrollY > 50) {
			header.classList.add('scrolled');
		} else {
			header.classList.remove('scrolled');
		}
	}
	updateScrolled();
	window.addEventListener('scroll', updateScrolled, { passive: true });

	// 4. Close on outside click
	document.addEventListener('click', function (e) {
		if (!header.contains(e.target)) {
			closeMobileMenu();
		}
	});
});