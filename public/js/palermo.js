/*!
 * PALERMOCRAFT — дрібна поведінка сайту без залежностей.
 * Сторінки працюють і без цього файлу: тут лише зручності.
 */
(function () {
    'use strict';

    var doc = document;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Копіювання адреси сервера, кодів тощо: <button data-copy="текст"> */
    function fallbackCopy(text) {
        var area = doc.createElement('textarea');
        area.value = text;
        area.setAttribute('readonly', '');
        area.style.position = 'fixed';
        area.style.opacity = '0';
        doc.body.appendChild(area);
        area.select();
        var ok = false;
        try { ok = doc.execCommand('copy'); } catch (e) { ok = false; }
        doc.body.removeChild(area);
        return ok;
    }

    function markCopied(btn) {
        var label = btn.querySelector('[data-copy-label]');
        btn.classList.add('is-copied');
        if (label) {
            if (!label.dataset.original) label.dataset.original = label.textContent;
            label.textContent = btn.getAttribute('data-copied') || 'Скопійовано';
        }
        clearTimeout(btn._copyTimer);
        btn._copyTimer = setTimeout(function () {
            btn.classList.remove('is-copied');
            if (label && label.dataset.original) label.textContent = label.dataset.original;
        }, 1800);
    }

    doc.addEventListener('click', function (event) {
        var btn = event.target.closest('[data-copy]');
        if (!btn) return;
        event.preventDefault();
        var text = btn.getAttribute('data-copy');
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function () { markCopied(btn); }, function () {
                if (fallbackCopy(text)) markCopied(btn);
            });
        } else if (fallbackCopy(text)) {
            markCopied(btn);
        }
    });

    /* Небезпечні дії підтверджуються другим натиском: <button data-confirm="Точно відвʼязати?"> */
    doc.addEventListener('click', function (event) {
        var btn = event.target.closest('[data-confirm]');
        if (!btn || btn.classList.contains('is-armed')) return;
        event.preventDefault();
        var original = btn.innerHTML;
        btn.classList.add('is-armed');
        btn.textContent = btn.getAttribute('data-confirm');
        setTimeout(function () {
            if (!btn.isConnected) return;
            btn.classList.remove('is-armed');
            btn.innerHTML = original;
        }, 4000);
    });

    /* Випадні меню на <details data-menu>: закриваються кліком поза ними та клавішею Esc */
    function closeMenus(except) {
        doc.querySelectorAll('details[data-menu][open]').forEach(function (menu) {
            if (menu !== except) menu.removeAttribute('open');
        });
    }
    doc.addEventListener('click', function (event) {
        var inside = event.target.closest('details[data-menu]');
        closeMenus(inside);
    });
    doc.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        var open = doc.querySelector('details[data-menu][open]');
        if (open) {
            open.removeAttribute('open');
            var summary = open.querySelector('summary');
            if (summary) summary.focus();
        }
    });

    /* Тінь шапки після прокручування */
    var header = doc.querySelector('[data-header]');
    if (header) {
        var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 8); };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* Плавна поява блоків під час прокручування */
    var reveal = doc.querySelectorAll('[data-reveal]');
    if (reveal.length) {
        if (!reduceMotion && 'IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -6% 0px', threshold: 0.06 });
            reveal.forEach(function (el) { observer.observe(el); });
        } else {
            reveal.forEach(function (el) { el.classList.add('is-visible'); });
        }
    }

    /* Поле файлу з перетягуванням: показує назву вибраного файлу */
    doc.querySelectorAll('[data-dropzone]').forEach(function (zone) {
        var input = zone.querySelector('input[type="file"]');
        var label = zone.querySelector('[data-dropzone-file]');
        if (!input) return;
        ['dragenter', 'dragover'].forEach(function (type) {
            zone.addEventListener(type, function () { zone.classList.add('is-dragover'); });
        });
        ['dragleave', 'drop'].forEach(function (type) {
            zone.addEventListener(type, function () { zone.classList.remove('is-dragover'); });
        });
        input.addEventListener('change', function () {
            if (label) label.textContent = input.files && input.files[0] ? input.files[0].name : '';
        });
    });
})();
