/* Social Booth Co Gallery: filters, "view more", lightbox, and booking-popup hand-off. */
(function () {
	'use strict';

	function init(root) {
		var perPage = parseInt(root.getAttribute('data-per-page'), 10) || 24;
		var items = Array.prototype.slice.call(root.querySelectorAll('.sbcg-item'));
		var chips = Array.prototype.slice.call(root.querySelectorAll('.sbcg-chip'));
		var moreBtn = root.querySelector('[data-sbcg-more]');
		var countEl = root.querySelector('[data-sbcg-count]');
		var filter = '*';
		var limit = perPage;

		/* "Check Your Date" opens the existing Elementor/UAEL Amelia popup if it's on the page;
		   otherwise the link's normal href is followed. */
		Array.prototype.forEach.call(root.querySelectorAll('[data-sbcg-modal]'), function (link) {
			link.addEventListener('click', function (e) {
				var id = link.getAttribute('data-sbcg-modal');
				var trigger = id && document.querySelector('.uael-trigger[data-modal="' + id + '"]');
				if (trigger) {
					e.preventDefault();
					trigger.click();
				}
			});
		});

		function matches(item) {
			return filter === '*' || (' ' + item.getAttribute('data-cats') + ' ').indexOf(' ' + filter + ' ') !== -1;
		}

		function matching() {
			return items.filter(matches);
		}

		var grid = root.querySelector('.sbcg-grid');

		function columns() {
			var n = parseInt(getComputedStyle(grid).getPropertyValue('--cols'), 10);
			return n > 0 ? n : 1;
		}

		function render(animateFrom) {
			var shown = 0;
			var total = 0;
			var cells = 0; // wide photos take 2 cells
			var cols = columns();
			items.forEach(function (item) {
				var ok = matches(item);
				if (ok) total++;
				// Past the page limit, keep going only until the last row is full (no lonely photo + empty gap).
				var visible = ok && (shown < limit || cells % cols !== 0);
				if (visible) {
					shown++;
					cells += item.classList.contains('is-wide') && cols > 1 ? 2 : 1;
					if (item.hidden && shown > animateFrom) {
						item.classList.remove('is-entering');
						void item.offsetWidth; // restart animation
						item.classList.add('is-entering');
					}
				}
				item.hidden = !visible;
			});
			if (moreBtn) moreBtn.hidden = shown >= total;
			if (countEl) countEl.textContent = total;
			visibleCount = shown;
		}

		var visibleCount = 0;
		var resizeTimer;
		window.addEventListener('resize', function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				limit = Math.max(limit, visibleCount);
				render(Infinity);
			}, 150);
		});

		chips.forEach(function (chip) {
			chip.addEventListener('click', function () {
				filter = chip.getAttribute('data-filter');
				limit = perPage;
				chips.forEach(function (c) {
					var on = c === chip;
					c.classList.toggle('is-active', on);
					c.setAttribute('aria-pressed', on ? 'true' : 'false');
				});
				render(0);
			});
		});

		if (moreBtn) {
			moreBtn.addEventListener('click', function () {
				var before = visibleCount;
				limit = visibleCount + perPage;
				render(before);
				// Move focus to the first newly revealed photo for keyboard users.
				var list = matching();
				if (list[before]) {
					var tile = list[before].querySelector('.sbcg-tile');
					if (tile) tile.focus({ preventScroll: true });
				}
			});
		}

		render(Infinity);

		/* Lightbox */
		var dialog = root.querySelector('.sbcg-lightbox');
		if (!dialog || typeof dialog.showModal !== 'function') return; // No-JS/old browsers: tiles link to the full image.

		var img = dialog.querySelector('.sbcg-lightbox__img');
		var caption = dialog.querySelector('[data-sbcg-lb-caption]');
		var pos = dialog.querySelector('[data-sbcg-lb-pos]');
		var list = [];
		var index = 0;
		var opener = null;

		function tileOf(item) {
			return item.querySelector('.sbcg-tile');
		}

		function preload(i) {
			var item = list[(i + list.length) % list.length];
			if (item) new Image().src = tileOf(item).href;
		}

		function show(i) {
			index = (i + list.length) % list.length;
			var tile = tileOf(list[index]);
			var thumb = tile.querySelector('img');
			img.src = tile.href;
			img.alt = thumb ? thumb.alt : '';
			caption.textContent = tile.getAttribute('data-caption') || '';
			pos.textContent = (index + 1) + ' / ' + list.length;
			preload(index + 1);
			preload(index - 1);
		}

		function close() {
			dialog.close();
		}

		dialog.addEventListener('close', function () {
			document.documentElement.style.overflow = '';
			img.removeAttribute('src');
			if (opener) opener.focus({ preventScroll: true });
		});

		root.querySelector('.sbcg-grid').addEventListener('click', function (e) {
			var tile = e.target.closest('.sbcg-tile');
			if (!tile || e.metaKey || e.ctrlKey || e.shiftKey || e.button > 0) return;
			e.preventDefault();
			list = matching();
			opener = tile;
			document.documentElement.style.overflow = 'hidden';
			dialog.showModal();
			show(list.indexOf(tile.parentNode));
		});

		dialog.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-sbcg-lb]');
			if (btn) {
				var action = btn.getAttribute('data-sbcg-lb');
				if (action === 'close') close();
				if (action === 'prev') show(index - 1);
				if (action === 'next') show(index + 1);
				return;
			}
			// Click on the dark area (not the photo) closes. Ignore the click that follows a swipe.
			if (Date.now() - swipedAt < 400) return;
			if (e.target === dialog || e.target.classList.contains('sbcg-lightbox__stage')) close();
		});

		dialog.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') { e.preventDefault(); show(index - 1); }
			if (e.key === 'ArrowRight') { e.preventDefault(); show(index + 1); }
		});

		/* Swipe left/right on touch screens */
		var startX = null;
		var startY = null;
		var swipedAt = 0;
		dialog.addEventListener('pointerdown', function (e) {
			if (e.pointerType === 'mouse') return;
			startX = e.clientX;
			startY = e.clientY;
		});
		dialog.addEventListener('pointerup', function (e) {
			if (startX === null) return;
			var dx = e.clientX - startX;
			var dy = e.clientY - startY;
			startX = null;
			if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy)) {
				swipedAt = Date.now();
				show(index + (dx < 0 ? 1 : -1));
			}
		});
	}

	function boot() {
		Array.prototype.forEach.call(document.querySelectorAll('.sbcg'), init);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
