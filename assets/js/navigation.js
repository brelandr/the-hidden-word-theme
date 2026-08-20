(function () {
	'use strict';

	var toggle = document.querySelector('.thw-nav-toggle');
	var nav = document.querySelector('.thw-nav');
	var header = document.querySelector('.thw-header');

	if (!toggle || !nav) {
		return;
	}

	function setNavOpen(open) {
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		nav.classList.toggle('is-open', open);
		if (header) {
			header.classList.toggle('is-nav-open', open);
		}
	}

	toggle.addEventListener('click', function () {
		var expanded = toggle.getAttribute('aria-expanded') === 'true';
		setNavOpen(!expanded);
	});

	var parentItems = nav.querySelectorAll('.thw-nav__list > li.menu-item-has-children > a');

	parentItems.forEach(function (link) {
		link.addEventListener('click', function (event) {
			if (window.matchMedia('(min-width: 769px)').matches) {
				return;
			}

			var parent = link.parentElement;
			if (!parent) {
				return;
			}

			var submenu = parent.querySelector(':scope > .sub-menu');
			if (!submenu) {
				return;
			}

			event.preventDefault();
			parent.classList.toggle('is-submenu-open');
			var open = parent.classList.contains('is-submenu-open');
			link.setAttribute('aria-expanded', open ? 'true' : 'false');

			// After expanding a long submenu, keep the parent row reachable while scrolling.
			if (open && header && typeof parent.scrollIntoView === 'function') {
				window.requestAnimationFrame(function () {
					parent.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
				});
			}
		});
	});
})();
