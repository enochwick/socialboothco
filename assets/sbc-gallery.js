/* Social Booth Co Gallery: filters, "view more", lightbox, and booking-popup hand-off. */
(function () {
	'use strict';

	function init(root) {
		var items = Array.prototype.slice.call(root.querySelectorAll('.sbcg-item'));
		var chips = Array.prototype.slice.call(root.querySelectorAll('.sbcg-chip'));
		var moreBtn = root.querySelector('[data-sbcg-more]');
		var countEl = root.querySelector('[data-sbcg-count]');
		var filter = '*';

		/* "Check Your Date" opens the existing Elementor/UAEL Amelia popup if it's on the page;
		   otherwise the link's normal href is followed. */
		Array.prototype.forEach.call(root.querySelectorAll('[data-sbcg-modal]'), function (link) {
			link.addEventListener('click', function (e) {
				var id = link.getAttribute('data-sbcg-modal');
				var safe = window.CSS && CSS.escape ? CSS.escape(id || '') : (id || '').replace(/[^\w-]/g, '');
				var trigger = safe && document.querySelector('.uael-trigger[data-modal="' + safe + '"]');
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

		/* Show whole rows only: 3 rows at first, then 3 more per "View More Moments". */
		var ROWS = parseInt(root.getAttribute('data-rows'), 10) || 3;
		var rows = ROWS;

		/* Photos per batch are measured on the desktop grid (4 columns), so phones and tablets show
		   the same photos as desktop, then fill out their last row so only whole rows appear. */
		var DESKTOP_COLS = parseInt(root.getAttribute('data-desktop-cols'), 10) || 4;

		function render(animateFrom) {
			var shown = 0;
			var total = 0;
			var cols = columns();
			var limit = rows * DESKTOP_COLS; // in desktop cells; wide photos take 2
			var deskCells = 0;
			var cells = 0; // cells on the current grid
			var filling = false; // past the desktop batch, only completing the last row
			items.forEach(function (item) {
				var ok = matches(item);
				if (ok) total++;
				var wide = item.classList.contains('is-wide');
				var deskSize = wide ? 2 : 1;
				var size = wide && cols > 1 ? 2 : 1;
				var visible = false;
				if (ok && !filling) {
					if (deskCells + deskSize <= limit) {
						visible = true;
						deskCells += deskSize;
					} else {
						filling = true;
					}
				}
				if (ok && filling && !visible && cells % cols !== 0 && size <= cols - (cells % cols)) {
					visible = true;
				}
				if (visible) {
					shown++;
					cells += size;
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
			resizeTimer = setTimeout(function () { render(Infinity); }, 150);
		});

		chips.forEach(function (chip) {
			chip.addEventListener('click', function () {
				filter = chip.getAttribute('data-filter');
				rows = ROWS;
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
				var wasVisible = matching().filter(function (it) { return !it.hidden; });
				rows += ROWS;
				render(before);
				// Move focus to the first newly revealed photo for keyboard users.
				var first = matching().filter(function (it) { return !it.hidden && wasVisible.indexOf(it) === -1; })[0];
				var tile = first && first.querySelector('.sbcg-tile');
				if (tile) tile.focus({ preventScroll: true });
			});
		}

		/* Shuffle once a day (same order on every device that day), keeping photos of the same kind
		   (category) apart on the desktop grid: no matching neighbor to the left or directly above.
		   Extra photos (data-extra, e.g. from the theme) join after the first batch, spread out. */
		(function shuffle() {
			var d = new Date();
			var seed = d.getFullYear() * 10000 + (d.getMonth() + 1) * 100 + d.getDate();
			var rand = function () { // mulberry32
				seed |= 0; seed = seed + 0x6D2B79F5 | 0;
				var t = Math.imul(seed ^ seed >>> 15, 1 | seed);
				t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t;
				return ((t ^ t >>> 14) >>> 0) / 4294967296;
			};
			var extras = items.filter(function (it) { return it.hasAttribute('data-extra'); });
			var pool = items.filter(function (it) { return !it.hasAttribute('data-extra'); });
			for (var i = pool.length - 1; i > 0; i--) {
				var j = Math.floor(rand() * (i + 1));
				var tmp = pool[i]; pool[i] = pool[j]; pool[j] = tmp;
			}
			var cats = function (it) { return (it.getAttribute('data-cats') || '').split(' ').filter(Boolean); };
			var shares = function (a, b) {
				if (!a || !b) return false;
				var cb = cats(b);
				return cats(a).some(function (c) { return cb.indexOf(c) !== -1; });
			};
			var cols = DESKTOP_COLS;
			var out = [];
			while (pool.length) {
				var prev = out[out.length - 1], above = out[out.length - cols];
				var pick = -1;
				for (var k = 0; k < pool.length; k++) {
					if (!shares(pool[k], prev) && !shares(pool[k], above)) { pick = k; break; }
				}
				if (pick === -1) {
					for (k = 0; k < pool.length; k++) { if (!shares(pool[k], prev)) { pick = k; break; } }
				}
				out.push(pool.splice(pick === -1 ? 0 : pick, 1)[0]);
			}
			// First batch = the photos desktop shows before "View More Moments".
			var first = 0, cells = 0;
			while (first < out.length) {
				var size = out[first].classList.contains('is-wide') ? 2 : 1;
				if (cells + size > ROWS * cols) break;
				cells += size; first++;
			}
			extras.forEach(function (it, n) {
				out.splice(Math.min(out.length, first + n * 3 + 1), 0, it);
			});
			out.forEach(function (it) { grid.appendChild(it); });
			items = out;
		})();

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
