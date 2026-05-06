/**
 * Harissa de Tunisie — Scripts principaux
 */
document.addEventListener('DOMContentLoaded', function () {

  // ============================================
  // 1. HEADER SCROLL EFFECT
  // ============================================
  const header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      header.classList.toggle('scrolled', window.scrollY > 40);
    });
  }

  // ============================================
  // 2. MOBILE MENU TOGGLE
  // ============================================
  const menuToggle = document.getElementById('menu-toggle');
  const mainNav = document.getElementById('main-nav');

  if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', function () {
      const isOpen = mainNav.classList.toggle('open');
      menuToggle.classList.toggle('active');
      menuToggle.setAttribute('aria-expanded', isOpen);
    });

    // Fermer le menu quand on clique sur un lien
    mainNav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        mainNav.classList.remove('open');
        menuToggle.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // ============================================
  // 3. ANIMATION AU SCROLL (Intersection Observer)
  // ============================================
  const animatedElements = document.querySelectorAll('.animate-on-scroll');

  if (animatedElements.length > 0 && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px',
      }
    );

    animatedElements.forEach(function (el) {
      observer.observe(el);
    });
  }

  // ============================================
  // 4. COMPTEURS ANIMÉS
  // ============================================
  const counters = document.querySelectorAll('.stat-number[data-target]');

  if (counters.length > 0 && 'IntersectionObserver' in window) {
    const counterObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            counterObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.5 }
    );

    counters.forEach(function (counter) {
      counterObserver.observe(counter);
    });
  }

  function animateCounter(el) {
    var target = parseInt(el.getAttribute('data-target'), 10);
    var suffix = el.textContent.replace(/[0-9]/g, '').trim();
    var duration = 2000;
    var startTime = null;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      // Easing: ease-out cubic
      var eased = 1 - Math.pow(1 - progress, 3);
      var current = Math.floor(eased * target);
      el.textContent = current.toLocaleString('fr-FR') + suffix;
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.textContent = target.toLocaleString('fr-FR') + suffix;
      }
    }

    requestAnimationFrame(step);
  }

  // ============================================
  // 5. SMOOTH SCROLL POUR LES ANCRES
  // ============================================
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var targetId = this.getAttribute('href');
      if (targetId === '#') return;
      var targetEl = document.querySelector(targetId);
      if (targetEl) {
        e.preventDefault();
        var offset = header ? header.offsetHeight + 20 : 80;
        var top = targetEl.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  // ============================================
  // 6. ACTIVE MENU ITEM ON SCROLL (optionnel)
  // ============================================
  var sections = document.querySelectorAll('section[id]');
  if (sections.length > 0 && header) {
    window.addEventListener('scroll', function () {
      var scrollPos = window.scrollY + header.offsetHeight + 100;
      sections.forEach(function (section) {
        var top = section.offsetTop;
        var bottom = top + section.offsetHeight;
        var id = section.getAttribute('id');
        var link = document.querySelector('.main-nav a[href*="#' + id + '"]');
        if (link) {
          if (scrollPos >= top && scrollPos < bottom) {
            link.parentElement.classList.add('current-menu-item');
          } else {
            link.parentElement.classList.remove('current-menu-item');
          }
        }
      });
    });
  }

});
