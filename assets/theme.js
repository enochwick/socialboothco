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

	/* Experience "How it works": lock the section while scrolling moves only the timeline,
	   then release to the next section. Desktop/tablet only; phones keep a normal scroll. */
	var pin = document.querySelector('[data-hiw-pin]');
	if (pin && window.matchMedia) {
		var mqPin = window.matchMedia('(min-width: 960px) and (min-height: 620px)');
		var viewport = pin.querySelector('.hiw-viewport');
		var list = pin.querySelector('.hiw-list');
		var steps = Array.prototype.slice.call(list.children);
		var overflow = 0;
		var PACE = 1.6; // page pixels scrolled per pixel of timeline movement (calmer pacing)
		var ticking = false;

		var reset = function () {
			pin.classList.remove('is-pinned');
			pin.style.height = '';
			list.style.transform = '';
			list.style.removeProperty('--p');
			steps.forEach(function (s) { s.classList.remove('is-active'); });
		};

		var update = function () {
			ticking = false;
			if (!pin.classList.contains('is-pinned')) return;
			var total = pin.offsetHeight - window.innerHeight;
			var p = total > 0 ? Math.min(1, Math.max(0, -pin.getBoundingClientRect().top / total)) : 0;
			list.style.transform = 'translate3d(0,' + (-p * overflow).toFixed(1) + 'px,0)';
			list.style.setProperty('--p', Math.max(0.04, p).toFixed(3));
			// Active step follows progress: step 1 at the start, the last step at the end.
			var active = Math.min(steps.length - 1, Math.floor(p * steps.length));
			steps.forEach(function (s, i) { s.classList.toggle('is-active', i === active); });
		};

		var layout = function () {
			reset();
			if (!mqPin.matches) return;
			pin.classList.add('is-pinned');
			overflow = Math.max(0, list.scrollHeight - viewport.clientHeight);
			if (overflow < 40) { reset(); return; } // everything already fits: no need to lock
			pin.style.height = Math.round(window.innerHeight + overflow * PACE) + 'px';
			update();
		};

		window.addEventListener('scroll', function () {
			if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
		}, { passive: true });
		var resizeT;
		window.addEventListener('resize', function () { clearTimeout(resizeT); resizeT = setTimeout(layout, 150); });
		if (mqPin.addEventListener) mqPin.addEventListener('change', layout);
		layout();
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(layout);
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
