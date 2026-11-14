/* Social Booth Co theme: mobile menu and booking links. */
(function () {
	'use strict';

	/* Mobile menu (native <dialog>: Esc closes, focus is trapped). */
	var menu = document.getElementById('mobile-menu');
	var opener = document.querySelector('[data-menu-open]');
	if (menu && opener && typeof menu.showModal === 'function') {
		opener.addEventListener('click', function () {
			menu.showModal();
			opener.setAttribute('aria-expanded', 'true');
		});
		menu.addEventListener('close', function () {
			opener.setAttribute('aria-expanded', 'false');
			opener.focus();
		});
		menu.addEventListener('click', function (e) {
			if (e.target.closest('[data-menu-close]')) menu.close();
			// Following an in-page link (e.g. #pricing) should close the menu.
			var link = e.target.closest('a');
			if (link) menu.close();
		});
	}

	/* Hero photo strip: pause/play (moving content must be stoppable). */
	var marquee = document.querySelector('[data-marquee]');
	var toggle = marquee && marquee.querySelector('[data-marquee-toggle]');
	if (toggle) {
		toggle.addEventListener('click', function () {
			var paused = marquee.classList.toggle('is-paused');
			toggle.querySelector('[data-label-pause]').hidden = paused;
			toggle.querySelector('[data-label-play]').hidden = !paused;
		});
	}

	/* Booking links are real links to the fallback page. When Amelia's popup is on the page,
	   it opens from the same click, so stop the browser from also navigating away. */
	document.addEventListener('click', function (e) {
		var link = e.target.closest('a.sbc-book, a.sbc-consult');
		if (!link || !window.sbcBooking) return;
		if (e.metaKey || e.ctrlKey || e.shiftKey || e.button > 0) return;
		var kind = link.classList.contains('sbc-consult') ? 'sbc-consult' : 'sbc-book';
		// Only intercept if Amelia actually rendered a form for this trigger.
		if (!document.querySelector('.sbc-amelia')) return;
		e.preventDefault();
		if (menu && menu.open) menu.close();
		link.setAttribute('data-sbc-clicked', kind);
	}, true);
})();
