(function () {
    'use strict';

    var toggle = document.querySelector('.menu-toggle');
    var navigation = document.querySelector('.primary-navigation');
    var header = document.querySelector('[data-site-header]');

    if (toggle && navigation) {
        toggle.addEventListener('click', function () {
            var isOpen = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!isOpen));
            navigation.classList.toggle('is-open', !isOpen);
            document.body.classList.toggle('menu-is-open', !isOpen);
        });

        navigation.addEventListener('click', function (event) {
            if (event.target.tagName === 'A') {
                toggle.setAttribute('aria-expanded', 'false');
                navigation.classList.remove('is-open');
                document.body.classList.remove('menu-is-open');
            }
        });
    }

    if (header) {
        var onScroll = function () {
            header.classList.toggle('is-scrolled', window.scrollY > 12);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    var filterButtons = document.querySelectorAll('[data-product-filter]');
    var productCards = document.querySelectorAll('[data-product-categories]');
    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var filter = button.getAttribute('data-product-filter');
            filterButtons.forEach(function (item) { item.classList.toggle('is-active', item === button); });
            productCards.forEach(function (card) {
                var categories = card.getAttribute('data-product-categories').split(' ');
                card.classList.toggle('is-filtered-out', filter !== 'all' && categories.indexOf(filter) === -1);
            });
        });
    });

    var revealItems = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealItems.forEach(function (item) { observer.observe(item); });
    } else {
        revealItems.forEach(function (item) { item.classList.add('is-visible'); });
    }
}());
