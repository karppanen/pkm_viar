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

    var heroSlider = document.querySelector('[data-hero-slider]');
    if (heroSlider) {
        var heroSlides = Array.prototype.slice.call(heroSlider.querySelectorAll('[data-hero-slide]'));
        var heroDots = Array.prototype.slice.call(heroSlider.querySelectorAll('[data-hero-dot]'));
        var heroNumber = heroSlider.querySelector('[data-hero-number]');
        var heroPrev = heroSlider.querySelector('[data-hero-prev]');
        var heroNext = heroSlider.querySelector('[data-hero-next]');
        var heroIndex = 0;
        var heroTimer = null;
        var heroInterval = parseInt(heroSlider.getAttribute('data-interval'), 10) || 5200;
        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var warmHeroImage = function (index) {
            if (!heroSlides.length) {
                return;
            }
            var slide = heroSlides[(index + heroSlides.length) % heroSlides.length];
            var slideImage = slide ? slide.querySelector('img') : null;
            if (slideImage) {
                slideImage.loading = 'eager';
            }
        };

        var showHeroSlide = function (nextIndex) {
            heroIndex = (nextIndex + heroSlides.length) % heroSlides.length;
            warmHeroImage(heroIndex);
            warmHeroImage(heroIndex + 1);
            heroSlides.forEach(function (slide, index) {
                var active = index === heroIndex;
                slide.classList.toggle('is-active', active);
                slide.setAttribute('aria-hidden', String(!active));
                slide.querySelectorAll('a').forEach(function (link) {
                    link.setAttribute('tabindex', active ? '0' : '-1');
                });
            });
            heroDots.forEach(function (dot, index) {
                var active = index === heroIndex;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-pressed', String(active));
            });
            if (heroNumber) {
                heroNumber.textContent = String(heroIndex + 1).padStart(2, '0') + ' / ' + String(heroSlides.length).padStart(2, '0');
            }
        };

        var stopHeroSlider = function () {
            if (heroTimer) {
                window.clearInterval(heroTimer);
                heroTimer = null;
            }
        };
        var startHeroSlider = function () {
            stopHeroSlider();
            if (!reducedMotion && heroSlides.length > 1 && !document.hidden) {
                heroTimer = window.setInterval(function () { showHeroSlide(heroIndex + 1); }, heroInterval);
            }
        };

        if (heroPrev) {
            heroPrev.addEventListener('click', function () { showHeroSlide(heroIndex - 1); startHeroSlider(); });
        }
        if (heroNext) {
            heroNext.addEventListener('click', function () { showHeroSlide(heroIndex + 1); startHeroSlider(); });
        }
        heroDots.forEach(function (dot) {
            dot.addEventListener('click', function () { showHeroSlide(parseInt(dot.getAttribute('data-hero-dot'), 10)); startHeroSlider(); });
        });
        heroSlider.addEventListener('mouseenter', stopHeroSlider);
        heroSlider.addEventListener('mouseleave', startHeroSlider);
        heroSlider.addEventListener('focusin', stopHeroSlider);
        heroSlider.addEventListener('focusout', startHeroSlider);
        document.addEventListener('visibilitychange', startHeroSlider);
        showHeroSlide(0);
        startHeroSlider();
    }

    document.querySelectorAll('[data-feature-slider]').forEach(function (slider) {
        var viewport = slider.querySelector('[data-feature-viewport]');
        var slides = Array.prototype.slice.call(slider.querySelectorAll('[data-feature-slide]'));
        var dots = Array.prototype.slice.call(slider.querySelectorAll('[data-feature-dot]'));
        var currentLabel = slider.querySelector('[data-feature-current]');
        var previous = slider.querySelector('[data-feature-prev]');
        var next = slider.querySelector('[data-feature-next]');
        var index = 0;
        var timer = null;
        var interval = parseInt(slider.getAttribute('data-interval'), 10) || 4200;
        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!viewport || !slides.length) {
            return;
        }

        var showFeature = function (nextIndex, smooth) {
            index = (nextIndex + slides.length) % slides.length;
            viewport.scrollTo({ left: slides[index].offsetLeft - viewport.offsetLeft, behavior: smooth ? 'smooth' : 'auto' });
            dots.forEach(function (dot, dotIndex) {
                var active = dotIndex === index;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-pressed', String(active));
            });
            if (currentLabel) {
                currentLabel.textContent = String(index + 1).padStart(2, '0');
            }
        };

        var stopFeatures = function () {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        };
        var startFeatures = function () {
            stopFeatures();
            if (!reducedMotion && slides.length > 1 && !document.hidden) {
                timer = window.setInterval(function () { showFeature(index + 1, true); }, interval);
            }
        };

        if (previous) {
            previous.addEventListener('click', function () { showFeature(index - 1, true); startFeatures(); });
        }
        if (next) {
            next.addEventListener('click', function () { showFeature(index + 1, true); startFeatures(); });
        }
        dots.forEach(function (dot) {
            dot.addEventListener('click', function () { showFeature(parseInt(dot.getAttribute('data-feature-dot'), 10), true); startFeatures(); });
        });
        slider.addEventListener('mouseenter', stopFeatures);
        slider.addEventListener('mouseleave', startFeatures);
        slider.addEventListener('focusin', stopFeatures);
        slider.addEventListener('focusout', startFeatures);
        window.addEventListener('resize', function () { showFeature(index, false); });
        document.addEventListener('visibilitychange', startFeatures);
        showFeature(0, false);
        startFeatures();
    });

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

    /* Lightweight visit tracking for the dealership dashboard. */
    var trackConfig = window.motoshopTrack || {};
    var trackEndpoint = trackConfig.endpoint;
    var trackNonce = trackConfig.nonce;

    var sendTrack = function (eventType, element) {
        if (!trackEndpoint || !trackNonce || !window.fetch) {
            return;
        }
        var body = new URLSearchParams();
        body.append('action', 'motoshop_track');
        body.append('nonce', trackNonce);
        body.append('event_type', eventType);
        body.append('path', window.location.pathname);
        body.append('product_id', element && element.getAttribute('data-product-id') ? element.getAttribute('data-product-id') : '');
        try {
            window.fetch(trackEndpoint, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: body.toString()
            });
        } catch (error) {
            /* Tracking must never break the page. */
        }
    };

    document.addEventListener('click', function (event) {
        var target = event.target;
        if (!target || !target.closest) {
            return;
        }
        var whatsapp = target.closest('a[href*="wa.me"]');
        if (whatsapp) {
            sendTrack('whatsapp', whatsapp);
            return;
        }
        var mapLink = target.closest('a[data-track="map"]');
        if (mapLink) {
            sendTrack('map', mapLink);
            return;
        }
        var request = target.closest('a[href*="#request-form"]');
        if (request) {
            sendTrack('request_start', request);
        }
    });
}());
