/* Social Booth Co chat assistant: launcher, panel and conversation. The server keeps the
   history; this keeps a copy in sessionStorage so the chat follows the visitor between pages. */
(function () {
	'use strict';

	var cfg = window.sbcChat;
	var root = document.getElementById('sbc-chat');
	if (!cfg || !root) return;

	var launcher = root.querySelector('[data-chat-open]');
	var panel = root.querySelector('[data-chat-panel]');
	var log = root.querySelector('[data-chat-log]');
	var form = root.querySelector('[data-chat-form]');
	var input = root.querySelector('[data-chat-input]');
	var chips = root.querySelector('[data-chat-chips]');
	var KEY = 'sbcChat';
	var busy = false;

	var newId = function () {
		if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
		var bytes = new Uint8Array(16);
		(window.crypto || window.msCrypto).getRandomValues(bytes);
		return Array.prototype.map.call(bytes, function (b) { return ('0' + b.toString(16)).slice(-2); }).join('');
	};

	var state = { id: '', msgs: [], open: false };
	try {
		var saved = JSON.parse(sessionStorage.getItem(KEY) || 'null');
		if (saved && saved.id) state = saved;
	} catch (e) { /* storage unavailable: start fresh */ }
	if (!state.id) state.id = newId();

	var save = function () {
		try { sessionStorage.setItem(KEY, JSON.stringify(state)); } catch (e) { /* ignore */ }
	};

	/* Plain text with clickable links and email addresses; never innerHTML. */
	var fill = function (el, text) {
		var re = /(https?:\/\/[^\s)]+|[\w.+-]+@[\w-]+\.[\w.-]+)/g;
		var last = 0, m;
		while ((m = re.exec(text))) {
			if (m.index > last) el.appendChild(document.createTextNode(text.slice(last, m.index)));
			var url = m[0].replace(/[.,]+$/, '');
			var a = document.createElement('a');
			a.href = url.indexOf('@') > -1 && url.indexOf('http') !== 0 ? 'mailto:' + url : url;
			a.textContent = url;
			el.appendChild(a);
			last = m.index + url.length;
			re.lastIndex = last;
		}
		if (last < text.length) el.appendChild(document.createTextNode(text.slice(last)));
	};

	var add = function (role, text) {
		var wrap = document.createElement('div');
		wrap.className = 'chat-msg chat-msg--' + role;
		var p = document.createElement('p');
		fill(p, text);
		wrap.appendChild(p);
		log.appendChild(wrap);
		log.scrollTop = log.scrollHeight;
		return wrap;
	};

	var typing = function (on) {
		var dots = log.querySelector('.chat-typing');
		if (on && !dots) {
			dots = document.createElement('div');
			dots.className = 'chat-msg chat-msg--bot chat-typing';
			dots.setAttribute('aria-label', 'Typing');
			dots.innerHTML = '<span></span><span></span><span></span>';
			log.appendChild(dots);
			log.scrollTop = log.scrollHeight;
		} else if (!on && dots) {
			dots.remove();
		}
	};

	var hideChips = function () { if (chips) chips.hidden = true; };

	var open = function () {
		panel.hidden = false;
		root.classList.add('is-open');
		launcher.setAttribute('aria-expanded', 'true');
		state.open = true;
		save();
		log.scrollTop = log.scrollHeight;
		// Don't pop the phone keyboard over the panel on open.
		if (window.matchMedia('(min-width: 600px)').matches) input.focus();
	};

	var close = function (refocus) {
		panel.hidden = true;
		root.classList.remove('is-open');
		launcher.setAttribute('aria-expanded', 'false');
		state.open = false;
		save();
		if (refocus !== false) launcher.focus();
	};

	var grow = function () {
		input.style.height = 'auto';
		input.style.height = Math.min(input.scrollHeight, 120) + 'px';
	};

	var send = function (text) {
		text = (text || '').trim().slice(0, cfg.maxChars || 800);
		if (!text || busy) return;
		busy = true;
		form.classList.add('is-busy');
		hideChips();
		add('user', text);
		state.msgs.push({ r: 'user', t: text });
		save();
		input.value = '';
		grow();
		typing(true);

		fetch(cfg.endpoint, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			credentials: 'same-origin',
			body: JSON.stringify({ session: state.id, message: text, page: location.href })
		})
			.then(function (res) { return res.json().catch(function () { return {}; }); })
			.then(function (data) { return data && data.reply ? data.reply : cfg.error; })
			.catch(function () { return cfg.error; })
			.then(function (reply) {
				typing(false);
				add('bot', reply);
				state.msgs.push({ r: 'bot', t: reply });
				save();
				busy = false;
				form.classList.remove('is-busy');
				if (!panel.hidden) input.focus();
			});
	};

	// Restore the conversation from earlier pages.
	state.msgs.forEach(function (m) { add(m.r, m.t); });
	if (state.msgs.length) hideChips();
	if (state.open) open();

	launcher.addEventListener('click', function () { panel.hidden ? open() : close(); });
	root.querySelector('[data-chat-close]').addEventListener('click', function () { close(); });
	panel.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

	form.addEventListener('submit', function (e) {
		e.preventDefault();
		send(input.value);
	});
	input.addEventListener('keydown', function (e) {
		if (e.key === 'Enter' && !e.shiftKey && !e.isComposing) {
			e.preventDefault();
			send(input.value);
		}
	});
	input.addEventListener('input', grow);

	if (chips) {
		chips.addEventListener('click', function (e) {
			var chip = e.target.closest('button');
			if (chip) send(chip.textContent);
		});
	}

	// "Check your date" opens Amelia's popup (theme.js); get the panel out of its way.
	root.querySelector('[data-chat-book]').addEventListener('click', function () { close(false); });
})();
