/* Social Booth Co theme: header, mobile menu, hero photo strip, timeline, booking links. */
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

	/* Header: brand background with white logo/menu once the page scrolls. */
	var header = document.querySelector('[data-site-header]');
	if (header) {
		var setScrolled = function () { header.classList.toggle('is-scrolled', window.scrollY > 12); };
		window.addEventListener('scroll', setScrolled, { passive: true });
		setScrolled();
	}

	/* Hero photo strip: plays on its own, pauses on hover/touch, and can be dragged
	   left or right with a mouse or finger. The pause button stops it for good. */
	var marquee = document.querySelector('[data-marquee]');
	var strip = marquee && marquee.querySelector('.mq-strip');
	var track = strip && strip.querySelector('.mq-track');
	if (track) {
		var toggle = marquee.querySelector('[data-marquee-toggle]');
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var SPEED = 32; // px per second
		var setW = 0, x = 0, last = 0, hover = false, drag = null, stopped = reduce;

		var measure = function () {
			var cards = track.children, half = cards.length / 2;
			setW = half >= 1 ? cards[half].offsetLeft - cards[0].offsetLeft : 0;
			if (!x) x = -setW * 0.25;
			wrap();
			paint();
		};
		var wrap = function () {
			if (setW <= 0) return;
			while (x > 0) x -= setW;
			while (x <= -setW) x += setW;
		};
		var paint = function () { track.style.transform = 'translate3d(' + x.toFixed(2) + 'px,0,0)'; };
		var setStopped = function (v) {
			stopped = v;
			marquee.classList.toggle('is-paused', v);
			if (toggle) {
				toggle.querySelector('[data-label-pause]').hidden = v;
				toggle.querySelector('[data-label-play]').hidden = !v;
			}
		};
		var frame = function (t) {
			var dt = last ? Math.min(0.05, (t - last) / 1000) : 0;
			last = t;
			if (!stopped && !hover && !drag && setW > 0 && !document.hidden) {
				x += SPEED * dt; // moves right, like the original marquee
				wrap();
				paint();
			}
			window.requestAnimationFrame(frame);
		};

		strip.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') hover = true; });
		strip.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') hover = false; });
		strip.addEventListener('pointerdown', function (e) {
			if (e.button > 0) return;
			drag = { id: e.pointerId, startX: e.clientX, startY: e.clientY, x0: x, moved: false, axis: '' };
		});
		strip.addEventListener('pointermove', function (e) {
			if (!drag || e.pointerId !== drag.id) return;
			var dx = e.clientX - drag.startX, dy = e.clientY - drag.startY;
			if (!drag.axis && (Math.abs(dx) > 6 || Math.abs(dy) > 6)) {
				drag.axis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
				if (drag.axis === 'x') { try { strip.setPointerCapture(e.pointerId); } catch (err) { /* pointer already gone */ } strip.classList.add('is-dragging'); }
			}
			if (drag.axis === 'y') { drag = null; return; } // let the page scroll vertically
			if (drag.axis === 'x') { x = drag.x0 + dx; wrap(); paint(); }
		});
		var endDrag = function (e) {
			if (!drag || (e && e.pointerId !== drag.id)) return;
			drag = null;
			strip.classList.remove('is-dragging');
			last = 0;
		};
		strip.addEventListener('pointerup', endDrag);
		strip.addEventListener('pointercancel', endDrag);
		strip.addEventListener('lostpointercapture', endDrag);

		if (toggle) toggle.addEventListener('click', function () { setStopped(!stopped); });
		setStopped(stopped);

		window.addEventListener('resize', measure);
		window.addEventListener('load', measure);
		if (document.fonts && document.fonts.ready) document.fonts.ready.then(measure);
		measure();
		window.requestAnimationFrame(frame);
	}

	/* Experience "How it works": lock the section while scrolling moves only the timeline,
	   then release to the next section. Desktop/tablet only; phones keep a normal scroll. */
	var pin = document.querySelector('[data-hiw-pin]');
	if (pin && window.matchMedia) {
		var mqPin = window.matchMedia('(min-width: 960px) and (min-height: 620px)');
		var viewport = pin.querySelector('.hiw-viewport');
		var sticky = pin.querySelector('.hiw-sticky');
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
			// The sticky area sits just below the sticky header.
			var total = pin.offsetHeight - sticky.offsetHeight;
			var stickTop = parseFloat(window.getComputedStyle(sticky).top) || 0;
			var p = total > 0 ? Math.min(1, Math.max(0, (stickTop - pin.getBoundingClientRect().top) / total)) : 0;
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
			pin.style.height = Math.round(sticky.offsetHeight + overflow * PACE) + 'px';
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

	/* Backdrop scroller. It moves on by one backdrop every few seconds and loops back at the end.
	   It waits while the pointer is over it, while it has keyboard focus, while it's off screen or the
	   tab is hidden, and stops for good once someone swipes it or presses pause. No auto-scroll with
	   reduced motion. The arrows page through it; swipes, trackpads and arrow keys scroll natively. */
	document.querySelectorAll('[data-scroller]').forEach(function (track) {
		var section = track.closest('section');
		var nav = section && section.querySelector('[data-scroller-nav]');
		if (!nav) return;
		var prev = nav.querySelector('[data-scroller-prev]');
		var next = nav.querySelector('[data-scroller-next]');
		var toggle = nav.querySelector('[data-scroller-toggle]');
		var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var motion = still ? 'auto' : 'smooth';
		var page = function () { return Math.max(track.clientWidth * 0.8, 200); };
		var step = function () {
			var item = track.firstElementChild;
			var gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
			return item ? item.getBoundingClientRect().width + gap : page();
		};
		var maxScroll = function () { return track.scrollWidth - track.clientWidth; };
		var sync = function () {
			var max = maxScroll();
			nav.hidden = max < 8;
			prev.disabled = track.scrollLeft < 8;
			next.disabled = track.scrollLeft > max - 8;
		};
		prev.addEventListener('click', function () { track.scrollBy({ left: -page(), behavior: motion }); });
		next.addEventListener('click', function () { track.scrollBy({ left: page(), behavior: motion }); });
		var raf = 0;
		track.addEventListener('scroll', function () {
			if (!raf) raf = window.requestAnimationFrame(function () { raf = 0; sync(); });
		}, { passive: true });
		window.addEventListener('resize', sync);
		sync();

		var delay = parseInt(track.getAttribute('data-scroller-auto'), 10);
		if (!delay || still || !toggle) return;
		var stopped = false, hovering = false, focused = false, onScreen = false;
		var setStopped = function (v) {
			stopped = v;
			toggle.classList.toggle('is-paused', v);
			toggle.setAttribute('aria-label', toggle.getAttribute(v ? 'data-label-play' : 'data-label-pause'));
		};
		toggle.hidden = false;
		toggle.addEventListener('click', function () { setStopped(!stopped); });
		track.addEventListener('mouseenter', function () { hovering = true; });
		track.addEventListener('mouseleave', function () { hovering = false; });
		section.addEventListener('focusin', function (e) { if (e.target !== toggle) focused = true; });
		section.addEventListener('focusout', function () { focused = false; });
		// A swipe or drag means the visitor has taken over.
		track.addEventListener('pointerdown', function (e) { if (e.pointerType !== 'mouse') setStopped(true); }, { passive: true });
		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (entries) { onScreen = entries[0].isIntersecting; }, { threshold: 0.3 }).observe(track);
		} else {
			onScreen = true;
		}
		window.setInterval(function () {
			if (stopped || hovering || focused || !onScreen || document.hidden) return;
			if (track.scrollLeft >= maxScroll() - 8) {
				track.scrollTo({ left: 0, behavior: motion });
			} else {
				track.scrollBy({ left: step(), behavior: motion });
			}
		}, delay);
	});

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
