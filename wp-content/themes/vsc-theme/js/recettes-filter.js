/**
 * Harissa — Recettes Archive Filter & Search
 * Filtrage côté client par type + recherche en temps réel
 */
document.addEventListener('DOMContentLoaded', function () {

  var tabs       = document.querySelectorAll('.filter-tab');
  var cards      = document.querySelectorAll('.rc-card');
  var grid       = document.getElementById('recettes-grid');
  var emptyState = document.getElementById('recettes-empty-state');
  var countEl    = document.getElementById('recettes-visible-count');
  var searchInput = document.getElementById('recettes-search-input');

  // Exit si pas sur la page recettes
  if (!grid || cards.length === 0) return;

  /**
   * Filtrer les cartes par type + recherche
   */
  function filterCards() {
    var activeTab = document.querySelector('.filter-tab.active');
    var filter = activeTab ? activeTab.getAttribute('data-filter') : 'all';
    var query = searchInput ? searchInput.value.toLowerCase().trim() : '';
    var visibleCount = 0;

    cards.forEach(function (card) {
      var cardType = card.getAttribute('data-type') || '';
      var cardName = card.getAttribute('data-name') || '';

      var matchType = (filter === 'all') || (cardType === filter);
      var matchSearch = !query || cardName.indexOf(query) !== -1;

      if (matchType && matchSearch) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    // Mettre à jour le compteur
    if (countEl) {
      countEl.textContent = visibleCount;
    }

    // Afficher/masquer l'empty state
    if (emptyState) {
      emptyState.style.display = (visibleCount === 0) ? 'block' : 'none';
    }
    if (grid) {
      grid.style.display = (visibleCount === 0) ? 'none' : 'grid';
    }
  }

  /**
   * Clic sur un onglet de filtre
   */
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      // Retirer .active de tous les tabs
      tabs.forEach(function (t) { t.classList.remove('active'); });
      // Activer le tab cliqué
      tab.classList.add('active');
      // Filtrer
      filterCards();
    });
  });

  /**
   * Recherche en temps réel
   */
  if (searchInput) {
    searchInput.addEventListener('input', filterCards);
  }

  /**
   * Animations d'apparition au scroll
   */
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    cards.forEach(function (card) {
      observer.observe(card);
    });
  } else {
    // Fallback : tout afficher
    cards.forEach(function (card) {
      card.classList.add('visible');
    });
  }

});
