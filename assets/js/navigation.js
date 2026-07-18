(function () {
	'use strict';

	var toggle = document.querySelector('.thw-nav-toggle');
	var nav = document.querySelector('.thw-nav');

	if (!toggle || !nav) {
		return;
	}

	toggle.addEventListener('click', function () {
		var expanded = toggle.getAttribute('aria-expanded') === 'true';
		toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
		nav.classList.toggle('is-open');
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
			link.setAttribute('aria-expanded', parent.classList.contains('is-submenu-open') ? 'true' : 'false');
		});
	});
})();
